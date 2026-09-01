<?php

namespace App\Support;

use Carbon\Carbon;

class HaddaCoverageCalculator
{
    public static function analyze($record): array
    {
        $from = self::normalizeNumber($record->from);
        $to = self::normalizeNumber($record->to);

        if ($from === null || $to === null || !self::validPageNumber($from) || !self::validPageNumber($to)) {
            return [
                'pages' => 0,
                'status' => 'unverified',
            ];
        }

        if ($from < $to) {
            return [
                'pages' => ($to - $from) + 1,
                'status' => 'page',
            ];
        }

        if ($from === $to) {
            return [
                'pages' => 1,
                'status' => 'page',
            ];
        }

        return [
            'pages' => 0,
            'status' => 'unverified',
        ];
    }

    public static function normalizeNumber($value): ?int
    {
        if ($value === null) {
            return null;
        }

        $normalized = strtr((string) $value, [
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
        ]);

        $normalized = trim(str_replace([',', ' '], '', $normalized));

        if (!preg_match('/^\d+$/', $normalized)) {
            return null;
        }

        return (int) $normalized;
    }

    public static function parseRecordDate(?string $date): ?Carbon
    {
        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date);
        } catch (\Exception $exception) {
            return null;
        }
    }

    private static function validPageNumber(int $number): bool
    {
        return $number >= 1 && $number <= 604;
    }
}
