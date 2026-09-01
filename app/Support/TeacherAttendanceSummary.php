<?php

namespace App\Support;

use Carbon\Carbon;
use App\Models\teachersAttendanceModel;

class TeacherAttendanceSummary
{
    public const ELIGIBILITY_PERCENTAGE = 60;

    private const PRESENT_STATUSES = ['present', 'closed early'];
    private const LATE_STATUSES = ['late', 'late with an excuse', 'came late and closed early'];
    private const ABSENT_STATUSES = ['absent'];
    private const EXCUSED_STATUSES = ['excused'];

    public static function forMonth(array $teacherIds, string $month, int $year): array
    {
        $teacherIds = array_values(array_unique(array_map('intval', $teacherIds)));

        if (empty($teacherIds)) {
            return [];
        }

        $recordsByTeacher = self::recordsForMonth($teacherIds, $month, $year)->groupBy(function ($record) {
            return (int) $record->teacher_id;
        });

        $summaries = [];

        foreach ($teacherIds as $teacherId) {
            $records = $recordsByTeacher->get($teacherId, collect());
            $present = self::countStatuses($records, self::PRESENT_STATUSES);
            $late = self::countStatuses($records, self::LATE_STATUSES);
            $absent = self::countStatuses($records, self::ABSENT_STATUSES);
            $excused = self::countStatuses($records, self::EXCUSED_STATUSES);
            $total = $records->count();
            $eligibleCount = $present + $late;
            $percentage = $total > 0 ? round(($eligibleCount / $total) * 100, 1) : 0;

            $summaries[$teacherId] = [
                'total' => $total,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'excused' => $excused,
                'eligible_count' => $eligibleCount,
                'percentage' => $percentage,
                'eligible' => $percentage >= self::ELIGIBILITY_PERCENTAGE,
            ];
        }

        return $summaries;
    }

    public static function recordsForMonth(array $teacherIds, string $month, int $year)
    {
        $teacherIds = array_values(array_unique(array_map('intval', $teacherIds)));

        if (empty($teacherIds)) {
            return collect();
        }

        $start = Carbon::parse("1 {$month} {$year}")->startOfMonth();
        $end = (clone $start)->endOfMonth();

        return teachersAttendanceModel::whereIn('teacher_id', $teacherIds)
            ->where('date', 'like', '%' . $year . '%')
            ->latest()
            ->get()
            ->filter(function ($record) use ($start, $end) {
                try {
                    $recordDate = Carbon::parse($record->date);
                } catch (\Exception $exception) {
                    return false;
                }

                return $recordDate->betweenIncluded($start, $end);
            })
            ->values();
    }

    public static function empty(): array
    {
        return [
            'total' => 0,
            'present' => 0,
            'late' => 0,
            'absent' => 0,
            'excused' => 0,
            'eligible_count' => 0,
            'percentage' => 0,
            'eligible' => false,
        ];
    }

    private static function countStatuses($records, array $statuses): int
    {
        return $records->filter(function ($record) use ($statuses) {
            return in_array(strtolower(trim((string) $record->status)), $statuses, true);
        })->count();
    }
}
