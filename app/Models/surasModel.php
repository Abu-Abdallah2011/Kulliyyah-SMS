<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surasModel extends Model
{
    use HasFactory;

    protected $table = 'suras';

    protected $fillable = [
        'sura',
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('sura', 'like', '%' . request('search') . '%');
        }
    }
}
