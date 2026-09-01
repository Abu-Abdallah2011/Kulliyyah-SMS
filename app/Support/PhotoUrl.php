<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class PhotoUrl
{
    public static function resolve(?string $photoPath): ?string
    {
        if (empty($photoPath)) {
            return null;
        }

        try {
            return Storage::disk('s3')->url($photoPath);
        } catch (\Throwable $e) {
            if (Storage::disk('public')->exists($photoPath)) {
                return Storage::disk('public')->url($photoPath);
            }

            if (file_exists(public_path('storage/' . $photoPath))) {
                return asset('storage/' . $photoPath);
            }
        }

        return null;
    }
}

