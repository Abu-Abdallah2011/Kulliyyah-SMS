<?php

namespace App\Support;

use App\Models\Hadda;

class HaddaRecordGuard
{
    public static function inspect($fromValue, $toValue, int $studentId, ?string $session, ?string $term, ?int $excludeId = null): array
    {
        $from = HaddaCoverageCalculator::normalizeNumber($fromValue);
        $to = HaddaCoverageCalculator::normalizeNumber($toValue);
        $errors = [];
        $warnings = [];

        if ($from === null || $to === null) {
            $errors[] = 'Please enter valid numeric Quran page numbers.';
        } elseif ($from < 1 || $to < 1 || $from > 604 || $to > 604) {
            $errors[] = 'Page numbers must be between 1 and 604.';
        } elseif ($from > $to) {
            $errors[] = 'The page range is in reverse order. Please enter the lower page first and the higher page last.';
        }

        if ($errors) {
            return [
                'from' => $from,
                'to' => $to,
                'errors' => $errors,
                'warnings' => $warnings,
                'overlaps' => [],
                'skipped_pages' => null,
            ];
        }

        $ranges = self::existingPageRanges($studentId, $session, $term, $excludeId);
        $overlaps = $ranges->filter(function ($range) use ($from, $to) {
            return $from <= $range['to'] && $to >= $range['from'];
        })->values();

        if ($overlaps->isNotEmpty()) {
            $errors[] = 'This page range overlaps with an existing Hadda record for this student in the same term.';
        }

        $latestRange = $ranges
            ->sortByDesc(fn ($range) => ($range['record_date_timestamp'] ?? 0).'-'.str_pad((string) $range['id'], 10, '0', STR_PAD_LEFT))
            ->first();

        $skippedPages = null;
        if ($latestRange && $to < ($latestRange['from'] - 1)) {
            $skippedPages = [
                'from' => $to + 1,
                'to' => $latestRange['from'] - 1,
            ];

            $warnings[] = self::formatSkippedPagesWarning($skippedPages);
        }

        return [
            'from' => $from,
            'to' => $to,
            'errors' => $errors,
            'warnings' => $warnings,
            'overlaps' => $overlaps->all(),
            'skipped_pages' => $skippedPages,
        ];
    }

    public static function existingPageRanges(int $studentId, ?string $session, ?string $term, ?int $excludeId = null)
    {
        return Hadda::query()
            ->where('student_id', $studentId)
            ->when($session, fn ($query) => $query->where('session', $session))
            ->when($term, fn ($query) => $query->where('term', $term))
            ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
            ->get()
            ->map(function ($record) {
                $coverage = HaddaCoverageCalculator::analyze($record);

                if ($coverage['status'] !== 'page') {
                    return null;
                }

                return [
                    'id' => $record->id,
                    'from' => HaddaCoverageCalculator::normalizeNumber($record->from),
                    'to' => HaddaCoverageCalculator::normalizeNumber($record->to),
                    'date' => $record->date,
                    'record_date_timestamp' => optional(HaddaCoverageCalculator::parseRecordDate($record->date))->timestamp ?? 0,
                ];
            })
            ->filter()
            ->sortBy('from')
            ->values();
    }

    public static function formatSkippedPagesWarning(?array $skippedPages): ?string
    {
        if (!$skippedPages) {
            return null;
        }

        if ($skippedPages['from'] === $skippedPages['to']) {
            return 'This record appears to skip page '.$skippedPages['from'].'. Please confirm the student really skipped it or correct the entry.';
        }

        return 'This record appears to skip pages '.$skippedPages['from'].'-'.$skippedPages['to'].'. Please confirm the student really skipped them or correct the entry.';
    }
}
