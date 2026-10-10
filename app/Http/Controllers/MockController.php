<?php

namespace App\Http\Controllers;

use App\Models\MockCycle;
use App\Models\MockRecord;
use App\Models\register_student;
use App\Models\register_teacher;
use App\Models\sessions;
use App\Models\surasModel;
use App\Support\AcademicPeriodResolver;
use App\Support\HaddaCoverageCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MockController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $this->currentTeacher();
        $hasManagementAccess = Gate::allows('isExecutive');

        $cycles = MockCycle::with(['student', 'teacher'])
            ->when(!$hasManagementAccess, function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher?->id ?? 0);
            })
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->input('cycle_type'), fn ($query, $type) => $query->where('cycle_type', $type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('Mock.index', [
            'cycles' => $cycles,
            'statusOptions' => MockCycle::statusOptions(),
            'typeOptions' => MockCycle::typeOptions(),
            'hasManagementAccess' => $hasManagementAccess,
        ]);
    }

    public function create()
    {
        abort_unless(Gate::allows('isExecutive'), 403);

        $students = register_student::query()
            ->where(function ($query) {
                $query->where('hadda_status', register_student::HADDA_STATUS_MOCK)
                    ->orWhere('tarteel_status', register_student::TARTEEL_STATUS_MOCK);
            })
            ->orderBy('fullname')
            ->get();

        $teachers = register_teacher::where('status', 'IN SCHOOL')
            ->orderBy('fullname')
            ->get();

        return view('Mock.create', [
            'students' => $students,
            'teachers' => $teachers,
            'typeOptions' => MockCycle::typeOptions(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Gate::allows('isExecutive'), 403);

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students_details_tables,id'],
            'teacher_id' => ['required', 'exists:teachers_details,id'],
            'cycle_type' => ['required', 'in:pre_mock,mock'],
            'start_date' => ['required', 'string', 'max:255'],
            'target_end_date' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $startDate = $this->parseDate($validated['start_date']) ?: now();
        $targetEndDate = $this->parseDate($validated['target_end_date'] ?? null);
        $period = AcademicPeriodResolver::forDate($validated['start_date']);
        $student = register_student::findOrFail($validated['student_id']);

        if ($student->hadda_status !== register_student::HADDA_STATUS_MOCK && $student->tarteel_status !== register_student::TARTEEL_STATUS_MOCK) {
            return back()->withInput()->with('message', 'Please mark the student as Hadda Mock Exam or Tarteel Mock Exam before assigning a mock cycle.');
        }

        $cycle = MockCycle::create([
            'student_id' => $validated['student_id'],
            'teacher_id' => $validated['teacher_id'],
            'cycle_type' => $validated['cycle_type'],
            'session' => $period?->session,
            'term' => $period?->term,
            'start_date' => $startDate->format('Y-m-d'),
            'target_end_date' => $targetEndDate?->format('Y-m-d'),
            'status' => MockCycle::STATUS_ACTIVE,
            'notes' => $validated['notes'] ?? null,
            'created_by_user_id' => Auth::id(),
            'created_by_name' => Auth::user()->username ?? Auth::user()->email,
        ]);

        return redirect()->route('mock_cycles.show', $cycle->id)->with('message', 'Mock cycle created and assigned successfully.');
    }

    public function show($id)
    {
        $cycle = MockCycle::with(['student', 'teacher', 'records' => fn ($query) => $query->orderByDesc('date')->orderByDesc('id')])->findOrFail($id);
        $this->authorizeCycleAccess($cycle);

        $records = $cycle->records;
        $totalPages = $records->sum(fn ($record) => $record->pageCount());
        $validRecords = $records->filter(fn ($record) => HaddaCoverageCalculator::analyze($record)['status'] === 'page')->count();
        $lastRecord = $records->sortByDesc('date')->first();

        return view('Mock.show', [
            'cycle' => $cycle,
            'records' => $records,
            'totalPages' => $totalPages,
            'validRecords' => $validRecords,
            'lastRecord' => $lastRecord,
            'hasManagementAccess' => Gate::allows('isExecutive'),
        ]);
    }

    public function createRecord($cycleId)
    {
        $cycle = MockCycle::with(['student', 'teacher'])->findOrFail($cycleId);
        $this->authorizeCycleAccess($cycle);

        abort_if($cycle->status !== MockCycle::STATUS_ACTIVE, 403, 'Only active mock cycles can receive new records.');

        return view('Mock.record_create', [
            'cycle' => $cycle,
            'sura' => surasModel::all(),
            'surah' => surasModel::all(),
        ]);
    }

    public function storeRecord(Request $request, $cycleId)
    {
        $cycle = MockCycle::with(['student', 'teacher'])->findOrFail($cycleId);
        $this->authorizeCycleAccess($cycle);

        abort_if($cycle->status !== MockCycle::STATUS_ACTIVE, 403, 'Only active mock cycles can receive new records.');

        [$validated, $fromSurah, $toSurah, $recordDate] = $this->validatedRecordData($request);
        $recordingTeacher = $this->currentTeacher() ?: $cycle->teacher;

        MockRecord::create([
            'mock_cycle_id' => $cycle->id,
            'student_id' => $cycle->student_id,
            'teacher_id' => $cycle->teacher_id,
            'date' => $recordDate->format('Y-m-d'),
            'sura' => $fromSurah->sura,
            'from' => $validated['from'],
            'to_surah' => $toSurah->sura,
            'to' => $validated['to'],
            'grade' => $validated['grade'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'recorded_by_user_id' => Auth::id(),
            'recorded_by_name' => $recordingTeacher?->fullname ?? Auth::user()->username ?? Auth::user()->email,
        ]);

        return redirect()->route('mock_cycles.show', $cycle->id)->with('message', 'Mock record added successfully.');
    }

    public function editRecord($id)
    {
        $record = MockRecord::with(['cycle.student', 'cycle.teacher'])->findOrFail($id);
        $this->authorizeCycleAccess($record->cycle);

        abort_if($record->cycle->status !== MockCycle::STATUS_ACTIVE, 403, 'Only active mock cycle records can be edited.');

        return view('Mock.record_edit', [
            'record' => $record,
            'cycle' => $record->cycle,
            'sura' => surasModel::all(),
            'surah' => surasModel::all(),
        ]);
    }

    public function updateRecord(Request $request, $id)
    {
        $record = MockRecord::with('cycle')->findOrFail($id);
        $this->authorizeCycleAccess($record->cycle);

        abort_if($record->cycle->status !== MockCycle::STATUS_ACTIVE, 403, 'Only active mock cycle records can be edited.');

        [$validated, $fromSurah, $toSurah, $recordDate] = $this->validatedRecordData($request);
        $recordingTeacher = $this->currentTeacher() ?: $record->cycle->teacher;

        $record->update([
            'date' => $recordDate->format('Y-m-d'),
            'sura' => $fromSurah->sura,
            'from' => $validated['from'],
            'to_surah' => $toSurah->sura,
            'to' => $validated['to'],
            'grade' => $validated['grade'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'recorded_by_user_id' => Auth::id(),
            'recorded_by_name' => $recordingTeacher?->fullname ?? Auth::user()->username ?? Auth::user()->email,
        ]);

        return redirect()->route('mock_cycles.show', $record->mock_cycle_id)->with('message', 'Mock record updated successfully.');
    }

    public function deleteRecord($id)
    {
        $record = MockRecord::with('cycle')->findOrFail($id);
        $cycleId = $record->mock_cycle_id;
        $this->authorizeCycleAccess($record->cycle);

        abort_if($record->cycle->status !== MockCycle::STATUS_ACTIVE, 403, 'Only active mock cycle records can be deleted.');

        $record->delete();

        return redirect()->route('mock_cycles.show', $cycleId)->with('message', 'Mock record deleted successfully.');
    }

    public function complete($id)
    {
        abort_unless(Gate::allows('isExecutive'), 403);

        $cycle = MockCycle::findOrFail($id);
        $cycle->update([
            'status' => MockCycle::STATUS_COMPLETED,
            'completed_at' => now()->format('Y-m-d'),
        ]);

        return back()->with('message', 'Mock cycle marked as completed.');
    }

    public function fail($id)
    {
        abort_unless(Gate::allows('isExecutive'), 403);

        MockCycle::where('id', $id)->update(['status' => MockCycle::STATUS_FAILED]);

        return back()->with('message', 'Mock cycle marked as failed.');
    }

    public function cancel($id)
    {
        abort_unless(Gate::allows('isExecutive'), 403);

        MockCycle::where('id', $id)->update(['status' => MockCycle::STATUS_CANCELLED]);

        return back()->with('message', 'Mock cycle cancelled.');
    }

    private function authorizeCycleAccess(MockCycle $cycle): void
    {
        if (Gate::allows('isExecutive')) {
            return;
        }

        abort_unless((int) ($this->currentTeacher()?->id ?? 0) === (int) $cycle->teacher_id, 403);
    }

    private function currentTeacher(): ?register_teacher
    {
        return register_teacher::where('user_id', Auth::id())->first();
    }

    private function validatedRecordData(Request $request): array
    {
        $validated = $request->validate([
            'date' => ['required', 'string', 'max:255'],
            'from_surah' => ['required', 'exists:suras,id'],
            'from' => ['required', 'string', 'max:255'],
            'to_surah' => ['required', 'exists:suras,id'],
            'to' => ['required', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $from = HaddaCoverageCalculator::normalizeNumber($validated['from']);
        $to = HaddaCoverageCalculator::normalizeNumber($validated['to']);

        if ($from === null || $to === null || $from < 1 || $to < 1 || $from > 604 || $to > 604 || $from > $to) {
            back()->withErrors(['from' => 'Please enter a valid Quran page range between 1 and 604.'])->withInput()->throwResponse();
        }

        return [
            $validated,
            surasModel::findOrFail($validated['from_surah']),
            surasModel::findOrFail($validated['to_surah']),
            $this->parseDate($validated['date']) ?: now(),
        ];
    }

    private function parseDate(?string $date): ?Carbon
    {
        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date)->startOfDay();
        } catch (\Exception $exception) {
            return null;
        }
    }
}
