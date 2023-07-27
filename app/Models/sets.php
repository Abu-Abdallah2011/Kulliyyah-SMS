<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sets extends Model
{
    use HasFactory;

    protected $table = 'sets';

    protected $fillable = [
        'set',
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('sets', 'like', '%' . request('search') . '%');
        }
    }
}
