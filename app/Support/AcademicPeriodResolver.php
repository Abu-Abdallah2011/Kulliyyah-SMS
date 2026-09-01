<?php

namespace App\Support;

use Carbon\Carbon;
use App\Models\sessions;

class AcademicPeriodResolver
{
    public static function forDate(?string $date): ?sessions
    {
        $recordDate = self::parseDate($date);

        if (!$recordDate) {
            return sessions::orderBy('created_at', 'desc')->first();
        }

        $periods = sessions::orderBy('created_at', 'desc')->get();

        foreach ($periods as $period) {
            $start = self::parseDate($period->term_starts);
            $end = self::parseDate($period->term_ends);

            if ($start && $end && $recordDate->betweenIncluded($start, $end)) {
                return $period;
            }
        }

        return sessions::orderBy('created_at', 'desc')->first();
    }

    private static function parseDate(?string $date): ?Carbon
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
