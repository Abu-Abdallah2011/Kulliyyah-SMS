<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subjectsModel extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = [
        'subject',
        'category',
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('subject', 'like', '%' . request('search') . '%');
            $query->where('category', 'like', '%' . request('search') . '%');
        }
    }
}
