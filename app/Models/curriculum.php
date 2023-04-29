<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class curriculum extends Model
{
    use HasFactory;

    // Surrender curriculum details to register_teacher model
    public function teacher()
    {
        return $this->belongsTo(register_teacher::class);
    }


    // Linking this model to register_student Model
    public function student()
    {
        return $this->belongsTo(register_student::class);
    }

    protected $table = 'curriculum';

    protected $fillable = [
        'date',
        'class',
        'sura',
        'from',
        'to',
        'times',
        'bita',
        'grade',
        'hadda',
        'comment',
        'teacher',
        'set',
        'session',
        'term',
        
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('date', 'like', '%' . request('search') . '%')
            ->orWhere('class', 'like', '%' . request('search') . '%')
            ->orWhere('sura', 'like', '%' . request('search') . '%')
            ->orWhere('from', 'like', '%' . request('search') . '%')
            ->orWhere('to', 'like', '%' . request('search') . '%')
            ->orWhere('times', 'like', '%' . request('search') . '%')
            ->orWhere('bita', 'like', '%' . request('search') . '%')
            ->orWhere('grade', 'like', '%' . request('search') . '%')
            ->orWhere('hadda', 'like', '%' . request('search') . '%')
            ->orWhere('comment', 'like', '%' . request('search') . '%')
            ->orWhere('teacher', 'like', '%' . request('search') . '%')
            ->orWhere('set', 'like', '%' . request('search') . '%')
            ;
        }
    }
}
