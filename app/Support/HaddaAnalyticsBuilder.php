<?php

namespace App\Support;

use App\Models\Hadda;
use App\Models\register_student;
use App\Models\sessions;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HaddaAnalyticsBuilder
{
    public static function build(?string $session, ?string $term, ?string $class = null, ?int $studentId = null): array
    {
        $period = self::period($session, $term);
        $session = $session ?: $period?->session;
        $term = $term ?: $period?->term;
        [$dateFrom, $dateTo] = self::dateRange($period);

        $students = register_student::query()
            ->whereNotNull('class')
            ->whereRaw("TRIM(class) != ''")
            ->where('class', 'not like', '%PRE%')
            ->whereRaw('LOWER(TRIM(class)) != ?', ['none for now'])
            ->whereRaw("TRIM(class) NOT LIKE '#%'")
            ->coverageLeaderboardEligible()
            ->when($class, fn ($query) => $query->where('class', $class))
            ->when($studentId, fn ($query) => $query->where('id', $studentId))
            ->orderBy('fullname')
            ->get();

        $records = Hadda::with('student')
            ->where('session', $session)
            ->where('term', $term)
            ->whereIn('student_id', $students->pluck('id'))
            ->get();

        $weeklyProgress = self::weeklyProgress($records, $dateFrom, $dateTo);
        $studentRows = self::studentRows($students, $records);
        $classRows = self::classRows($students, $studentRows);
        $totalPoints = round($studentRows->sum('leaderboard_points'), 1);
        $studentCount = $students->count();
        $recordedStudentCount = $studentRows->where('record_count', '>', 0)->count();

        return [
            'summary' => [
                'student_count' => $studentCount,
                'recorded_student_count' => $recordedStudentCount,
                'no_record_count' => max(0, $studentCount - $recordedStudentCount),
                'total_pages' => (int) $studentRows->sum('confirmed_pages'),
                'total_points' => $totalPoints,
                'valid_records' => (int) $studentRows->sum('valid_records'),
                'uncounted_records' => (int) $studentRows->sum('uncounted_records'),
                'average_points_per_student' => $studentCount > 0 ? round($totalPoints / $studentCount, 1) : 0,
                'last_record_date' => self::lastRecordDate($studentRows),
                'trend' => self::trendLabel($weeklyProgress),
            ],
            'studentRows' => $studentRows,
            'classRows' => $classRows,
            'topStudents' => $studentRows->sortBy('rank')->take(5)->values(),
            'bottomStudents' => $studentRows->sortBy([
                ['leaderboard_points', 'asc'],
                ['confirmed_pages', 'asc'],
                ['fullname', 'asc'],
            ])->take(5)->values(),
            'weeklyProgress' => $weeklyProgress,
            'period' => [
                'session' => $session,
                'term' => $term,
                'term_starts' => $period?->term_starts,
                'term_ends' => $period?->term_ends,
            ],
        ];
    }

    public static function classes(): Collection
    {
        return register_student::query()
            ->whereNotNull('class')
            ->whereRaw("TRIM(class) != ''")
            ->where('class', 'not like', '%PRE%')
            ->whereRaw('LOWER(TRIM(class)) != ?', ['none for now'])
            ->whereRaw("TRIM(class) NOT LIKE '#%'")
            ->coverageLeaderboardEligible()
            ->orderBy('class')
            ->pluck('class')
            ->unique()
            ->values();
    }

    private static function studentRows(Collection $students, Collection $records): Collection
    {
        $recordsByStudent = $records->groupBy('student_id');

        return $students->map(function ($student) use ($recordsByStudent) {
            $studentRecords = $recordsByStudent->get($student->id, collect());
            $confirmedPages = 0;
            $validRecords = 0;
            $uncountedRecords = 0;
            $lastRecordDate = null;

            foreach ($studentRecords as $record) {
                $coverage = HaddaCoverageCalculator::analyze($record);
                $confirmedPages += $coverage['pages'];

                if ($coverage['status'] === 'page') {
                    $validRecords++;
                } else {
                    $uncountedRecords++;
                }

                $recordDate = HaddaCoverageCalculator::parseRecordDate($record->date);
                if ($recordDate && (!$lastRecordDate || $recordDate->greaterThan($lastRecordDate))) {
                    $lastRecordDate = $recordDate;
                }
            }

            return [
                'student' => $student,
                'student_id' => $student->id,
                'fullname' => $student->fullname,
                'class' => $student->class,
                'confirmed_pages' => $confirmedPages,
                'leaderboard_points' => self::leaderboardPoints($confirmedPages, $student),
                'valid_records' => $validRecords,
                'record_count' => $studentRecords->count(),
                'uncounted_records' => $uncountedRecords,
                'last_record_date' => $lastRecordDate,
            ];
        })
            ->sort(function ($a, $b) {
                return [$b['leaderboard_points'], $b['confirmed_pages'], $b['valid_records'], optional($b['last_record_date'])->timestamp ?? 0]
                    <=> [$a['leaderboard_points'], $a['confirmed_pages'], $a['valid_records'], optional($a['last_record_date'])->timestamp ?? 0];
            })
            ->values()
            ->map(function ($row, $index) {
                $row['rank'] = $index + 1;
                return $row;
            });
    }

    private static function classRows(Collection $students, Collection $studentRows): Collection
    {
        return $students->groupBy('class')
            ->map(function ($classStudents, $className) use ($studentRows) {
                $rows = $studentRows->where('class', $className);
                $studentCount = $classStudents->count();
                $points = round($rows->sum('leaderboard_points'), 1);

                return [
                    'class' => $className,
                    'student_count' => $studentCount,
                    'recorded_student_count' => $rows->where('record_count', '>', 0)->count(),
                    'confirmed_pages' => (int) $rows->sum('confirmed_pages'),
                    'leaderboard_points' => $points,
                    'average_points_per_student' => $studentCount > 0 ? round($points / $studentCount, 1) : 0,
                    'valid_records' => (int) $rows->sum('valid_records'),
                    'uncounted_records' => (int) $rows->sum('uncounted_records'),
                ];
            })
            ->sort(function ($a, $b) {
                return [$b['average_points_per_student'], $b['leaderboard_points'], $b['confirmed_pages'], $b['valid_records']]
                    <=> [$a['average_points_per_student'], $a['leaderboard_points'], $a['confirmed_pages'], $a['valid_records']];
            })
            ->values()
            ->map(function ($row, $index) {
                $row['rank'] = $index + 1;
                return $row;
            });
    }

    private static function weeklyProgress(Collection $records, ?Carbon $dateFrom, ?Carbon $dateTo): Collection
    {
        $weeks = self::weeks($dateFrom, $dateTo, $records);

        return $weeks->map(function ($week) use ($records) {
            $weekRecords = $records->filter(function ($record) use ($week) {
                $recordDate = HaddaCoverageCalculator::parseRecordDate($record->date);

                return $recordDate && $recordDate->betweenIncluded($week['start'], $week['end']);
            });

            $pages = 0;
            $points = 0;
            $validRecords = 0;
            $uncountedRecords = 0;

            foreach ($weekRecords as $record) {
                $coverage = HaddaCoverageCalculator::analyze($record);
                $pages += $coverage['pages'];
                $points += self::leaderboardPoints($coverage['pages'], $record->student);

                if ($coverage['status'] === 'page') {
                    $validRecords++;
                } else {
                    $uncountedRecords++;
                }
            }

            return [
                'label' => $week['start']->format('M d'),
                'range' => $week['start']->format('M d').' - '.$week['end']->format('M d'),
                'pages' => $pages,
                'points' => round($points, 1),
                'valid_records' => $validRecords,
                'uncounted_records' => $uncountedRecords,
            ];
        });
    }

    private static function weeks(?Carbon $dateFrom, ?Carbon $dateTo, Collection $records): Collection
    {
        if (!$dateFrom || !$dateTo) {
            $dates = $records
                ->map(fn ($record) => HaddaCoverageCalculator::parseRecordDate($record->date))
                ->filter();

            $dateFrom = $dates->min()?->copy()->startOfDay() ?: now()->startOfDay();
            $dateTo = $dates->max()?->copy()->endOfDay() ?: now()->endOfDay();
        }

        $weeks = collect();
        $cursor = $dateFrom->copy()->startOfDay();
        $end = $dateTo->copy()->endOfDay();

        while ($cursor->lessThanOrEqualTo($end)) {
            $weekStart = $cursor->copy();
            $daysUntilFriday = (Carbon::FRIDAY - $weekStart->dayOfWeek + 7) % 7;
            $weekEnd = $weekStart->copy()->addDays($daysUntilFriday)->endOfDay();

            $weeks->push([
                'start' => $weekStart,
                'end' => $weekEnd->greaterThan($end) ? $end->copy() : $weekEnd,
            ]);

            $cursor = $weekEnd->copy()->addDay()->startOfDay();
        }

        return $weeks;
    }

    private static function period(?string $session, ?string $term): ?sessions
    {
        return sessions::query()
            ->when($session, fn ($query) => $query->where('session', $session))
            ->when($term, fn ($query) => $query->where('term', $term))
            ->latest()
            ->first();
    }

    private static function dateRange(?sessions $period): array
    {
        try {
            $dateFrom = $period?->term_starts ? Carbon::parse($period->term_starts)->startOfDay() : null;
            $dateTo = $period?->term_ends ? Carbon::parse($period->term_ends)->endOfDay() : null;
        } catch (\Exception $exception) {
            return [null, null];
        }

        return [$dateFrom, $dateTo];
    }

    private static function trendLabel(Collection $weeklyProgress): string
    {
        $weeksWithPoints = $weeklyProgress->filter(fn ($week) => (float) $week['points'] > 0)->values();

        if ($weeksWithPoints->count() < 2) {
            return $weeksWithPoints->isEmpty() ? 'No Record' : 'Started';
        }

        $half = (int) ceil($weeksWithPoints->count() / 2);
        $firstHalf = $weeksWithPoints->take($half)->avg('points') ?: 0;
        $secondHalf = $weeksWithPoints->slice($half)->avg('points') ?: 0;

        if ($secondHalf > ($firstHalf * 1.15)) {
            return 'Improving';
        }

        if ($secondHalf < ($firstHalf * 0.85)) {
            return 'Declining';
        }

        return 'Stable';
    }

    private static function lastRecordDate(Collection $studentRows): ?Carbon
    {
        return $studentRows
            ->pluck('last_record_date')
            ->filter()
            ->sortByDesc(fn ($date) => $date->timestamp)
            ->first();
    }

    private static function leaderboardPoints(int|float $pages, ?register_student $student): float
    {
        if ($student?->tarteel_status === register_student::TARTEEL_STATUS_ACTIVE) {
            return round($pages / 2, 1);
        }

        return round($pages, 1);
    }
}
