<?php

namespace App\Support;

use App\Models\Hadda;
use App\Models\register_student;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HaddaLeaderboardBuilder
{
    public static function build(?string $session, ?string $term, ?string $class = null, $dateFrom = null, $dateTo = null, bool $includeStudentsWithoutRecords = false): array
    {
        $dateFrom = self::parseDate($dateFrom);
        $dateTo = self::parseDate($dateTo)?->endOfDay();

        $eligibleStudents = register_student::query()
            ->tap(fn ($query) => self::applyEligibleClassFilter($query))
            ->coverageLeaderboardEligible()
            ->when($class, function ($query) use ($class) {
                $query->where('class', $class);
            })
            ->orderBy('fullname')
            ->get();

        $records = Hadda::with('student')
            ->where('session', $session)
            ->where('term', $term)
            ->whereIn('student_id', $eligibleStudents->pluck('id'))
            ->get()
            ->filter(function ($record) use ($dateFrom, $dateTo) {
                if (!$dateFrom || !$dateTo) {
                    return true;
                }

                $recordDate = HaddaCoverageCalculator::parseRecordDate($record->date);

                return $recordDate && $recordDate->betweenIncluded($dateFrom, $dateTo);
            });

        $studentRankings = $records
            ->groupBy('student_id')
            ->map(function ($studentRecords) {
                $firstRecord = $studentRecords->first();
                $confirmedPages = 0;
                $validRecords = 0;
                $verseBasedRecords = 0;
                $unverifiedRecords = 0;
                $scoreTotal = 0;
                $scoreCount = 0;
                $lastRecordDate = null;

                foreach ($studentRecords as $record) {
                    $coverage = HaddaCoverageCalculator::analyze($record);
                    $confirmedPages += $coverage['pages'];

                    if ($coverage['status'] === 'page') {
                        $validRecords++;
                    } elseif ($coverage['status'] === 'verse') {
                        $verseBasedRecords++;
                    } else {
                        $unverifiedRecords++;
                    }

                    if (is_numeric($record->score)) {
                        $scoreTotal += (float) $record->score;
                        $scoreCount++;
                    }

                    $recordDate = HaddaCoverageCalculator::parseRecordDate($record->date);
                    if ($recordDate && (!$lastRecordDate || $recordDate->greaterThan($lastRecordDate))) {
                        $lastRecordDate = $recordDate;
                    }
                }
                $student = $firstRecord->student;
                $leaderboardPoints = self::leaderboardPoints($confirmedPages, $student);

                return [
                    'student' => $student,
                    'class' => $student?->class ?? $firstRecord->class,
                    'set' => $student?->set,
                    'confirmed_pages' => $confirmedPages,
                    'leaderboard_points' => $leaderboardPoints,
                    'valid_records' => $validRecords,
                    'record_count' => $studentRecords->count(),
                    'verse_based_records' => $verseBasedRecords,
                    'unverified_records' => $unverifiedRecords,
                    'uncounted_records' => $verseBasedRecords + $unverifiedRecords,
                    'average_score' => $scoreCount > 0 ? round($scoreTotal / $scoreCount, 1) : 0,
                    'score_total' => $scoreTotal,
                    'score_count' => $scoreCount,
                    'last_record_date' => $lastRecordDate,
                ];
            })
            ->sort(function ($a, $b) {
                return [$b['leaderboard_points'], $b['confirmed_pages'], $b['average_score'], $b['valid_records'], optional($b['last_record_date'])->timestamp ?? 0]
                    <=> [$a['leaderboard_points'], $a['confirmed_pages'], $a['average_score'], $a['valid_records'], optional($a['last_record_date'])->timestamp ?? 0];
            })
            ->values();

        if ($includeStudentsWithoutRecords) {
            $rankedStudentIds = $studentRankings
                ->map(fn ($row) => (int) ($row['student']?->id ?? 0))
                ->filter()
                ->all();

            $studentsWithoutRecords = $eligibleStudents
                ->reject(fn ($student) => in_array((int) $student->id, $rankedStudentIds, true))
                ->map(function ($student) {
                    return [
                        'student' => $student,
                        'class' => $student->class,
                        'set' => $student->set,
                        'confirmed_pages' => 0,
                        'leaderboard_points' => 0,
                        'valid_records' => 0,
                        'record_count' => 0,
                        'verse_based_records' => 0,
                        'unverified_records' => 0,
                        'uncounted_records' => 0,
                        'average_score' => 0,
                        'score_total' => 0,
                        'score_count' => 0,
                        'last_record_date' => null,
                    ];
                });

            $studentRankings = $studentRankings->concat($studentsWithoutRecords)->values();
        }

        $studentRankings = self::withRankContext($studentRankings, 'leaderboard_points');
        $studentRankingsByClass = $studentRankings->groupBy('class');

        $classRankings = $eligibleStudents
            ->groupBy('class')
            ->map(function ($students, $className) use ($studentRankingsByClass) {
                $rows = $studentRankingsByClass->get($className, collect());
                $recordedRows = $rows->filter(fn ($row) => (int) ($row['record_count'] ?? 0) > 0);
                $studentCount = $students->count();
                $confirmedPages = $rows->sum('confirmed_pages');
                $leaderboardPoints = $rows->sum('leaderboard_points');
                $averagePoints = $studentCount > 0 ? round($leaderboardPoints / $studentCount, 1) : 0;

                return [
                    'class' => $className,
                    'student_count' => $studentCount,
                    'recorded_student_count' => $recordedRows->count(),
                    'confirmed_pages' => $confirmedPages,
                    'leaderboard_points' => $leaderboardPoints,
                    'average_points_per_student' => $averagePoints,
                    'average_pages_per_student' => $averagePoints,
                    'valid_records' => $rows->sum('valid_records'),
                    'uncounted_records' => $rows->sum('uncounted_records'),
                ];
            })
            ->sort(function ($a, $b) {
                return [$b['average_points_per_student'], $b['leaderboard_points'], $b['confirmed_pages'], $b['valid_records']]
                    <=> [$a['average_points_per_student'], $a['leaderboard_points'], $a['confirmed_pages'], $a['valid_records']];
            })
            ->values();

        $classRankings = self::withRankContext($classRankings, 'average_points_per_student');
        $excludedStudents = collect();

        if ($includeStudentsWithoutRecords && $class) {
            $excludedStudents = register_student::query()
                ->tap(fn ($query) => self::applyEligibleClassFilter($query))
                ->where('class', $class)
                ->currentlyEnrolled()
                ->whereNotIn('id', $eligibleStudents->pluck('id'))
                ->orderBy('fullname')
                ->get();
        }

        return [
            'studentRankings' => $studentRankings,
            'topStudents' => $studentRankings->take(3),
            'classRankings' => $classRankings,
            'excludedStudents' => $excludedStudents,
            'classes' => register_student::query()
                ->tap(fn ($query) => self::applyEligibleClassFilter($query))
                ->coverageLeaderboardEligible()
                ->orderBy('class')
                ->pluck('class')
                ->unique()
                ->values(),
        ];
    }

    private static function applyEligibleClassFilter($query)
    {
        return $query
            ->whereNotNull('class')
            ->whereRaw("TRIM(class) != ''")
            ->where('class', 'not like', '%PRE%')
            ->whereRaw('LOWER(TRIM(class)) != ?', ['none for now'])
            ->whereRaw("TRIM(class) NOT LIKE '#%'");
    }

    public static function studentRankSummary(register_student $student, ?string $session, ?string $term, $dateFrom = null, $dateTo = null): array
    {
        $schoolData = self::build($session, $term, null, $dateFrom, $dateTo);
        $classData = self::build($session, $term, $student->class, $dateFrom, $dateTo);

        return [
            'school' => self::findStudentRow($schoolData['studentRankings'], $student->id),
            'class' => self::findStudentRow($classData['studentRankings'], $student->id),
            'class_name' => $student->class,
            'school_count' => $schoolData['studentRankings']->count(),
            'class_count' => $classData['studentRankings']->count(),
        ];
    }

    public static function rankingPeriodLabel(?string $session, ?string $term, ?Carbon $dateFrom = null, ?Carbon $dateTo = null): string
    {
        if ($dateFrom && $dateTo) {
            return 'Weekly Ranking: '.$dateFrom->format('M d, Y').' - '.$dateTo->format('M d, Y').' ('.$term.' '.$session.')';
        }

        return 'Term Ranking: '.$term.' '.$session;
    }

    private static function withRankContext(Collection $rows, string $valueKey = 'confirmed_pages'): Collection
    {
        return $rows->values()->map(function ($row, $index) use ($rows, $valueKey) {
            $above = $index > 0 ? $rows[$index - 1] : null;
            $below = $index < ($rows->count() - 1) ? $rows[$index + 1] : null;
            $currentValue = (float) ($row[$valueKey] ?? 0);

            $row['rank'] = $index + 1;
            $row['gap_to_above'] = $above ? max(0, round(((float) ($above[$valueKey] ?? 0)) - $currentValue, 1)) : null;
            $row['gap_to_below'] = $below ? max(0, round($currentValue - ((float) ($below[$valueKey] ?? 0)), 1)) : null;
            $row['above_name'] = $above ? self::rowLabel($above) : null;
            $row['below_name'] = $below ? self::rowLabel($below) : null;

            return $row;
        });
    }

    private static function findStudentRow(Collection $rows, int $studentId): ?array
    {
        return $rows->first(function ($row) use ($studentId) {
            return (int) ($row['student']?->id ?? 0) === $studentId;
        });
    }

    private static function rowLabel(array $row): ?string
    {
        if (isset($row['student'])) {
            return $row['student']?->fullname;
        }

        return $row['class'] ?? null;
    }

    private static function leaderboardPoints(int|float $pages, ?register_student $student): float
    {
        if ($student?->tarteel_status === register_student::TARTEEL_STATUS_ACTIVE) {
            return round($pages / 2, 1);
        }

        return round($pages, 1);
    }

    private static function parseDate($date): ?Carbon
    {
        if (!$date) {
            return null;
        }

        if ($date instanceof Carbon) {
            return $date->copy()->startOfDay();
        }

        try {
            return Carbon::parse($date)->startOfDay();
        } catch (\Exception $exception) {
            return null;
        }
    }
}
