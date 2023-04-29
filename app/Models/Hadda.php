<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hadda extends Model
{
    use HasFactory;

    // Linking this model to register_student Model
    public function student()
    {
        return $this->belongsTo(register_student::class);
    }

    protected $table = '_hadda';

    protected $fillable = [
        'date',
        'class',
        'name',
        'sura',
        'from',
        'to',
        'grade',
        'comment',
        'teacher',
        'term',
        'session',
        'student_id',        
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('date', 'like', '%' . request('search') . '%')
            ->orWhere('class', 'like', '%' . request('search') . '%')
            ->orWhere('name', 'like', '%' . request('search') . '%')
            ->orWhere('sura', 'like', '%' . request('search') . '%')
            ->orWhere('from', 'like', '%' . request('search') . '%')
            ->orWhere('to', 'like', '%' . request('search') . '%')
            ->orWhere('grade', 'like', '%' . request('search') . '%')
            ->orWhere('comment', 'like', '%' . request('search') . '%')
            ->orWhere('teacher', 'like', '%' . request('search') . '%')
            ->orWhere('term', 'like', '%' . request('search') . '%')
            ->orWhere('session', 'like', '%' . request('search') . '%')
            ->orWhere('student_id', 'like', '%' . request('search') . '%')
            ;
        }

    }
}
