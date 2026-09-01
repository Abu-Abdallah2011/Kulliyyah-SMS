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
        return $this->belongsTo(register_student::class, 'student_id', 'id');
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
        'to_surah',        
        'score',        
    ];

    public function scopeOrderByHaddaDate($query, string $direction = 'desc')
    {
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        return $query
            ->orderByRaw("STR_TO_DATE(`date`, '%W, %M %d, %Y') {$direction}")
            ->orderBy('id', $direction);
    }

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $search = request('search');

            $query->where(function ($query) use ($search) {
                $query->where('date', 'like', '%' . $search . '%')
                ->orWhere('class', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('sura', 'like', '%' . $search . '%')
                ->orWhere('from', 'like', '%' . $search . '%')
                ->orWhere('to', 'like', '%' . $search . '%')
                ->orWhere('grade', 'like', '%' . $search . '%')
                ->orWhere('comment', 'like', '%' . $search . '%')
                ->orWhere('teacher', 'like', '%' . $search . '%')
                ->orWhere('term', 'like', '%' . $search . '%')
                ->orWhere('session', 'like', '%' . $search . '%')
                ->orWhere('student_id', 'like', '%' . $search . '%')
                ->orWhere('to_surah', 'like', '%' . $search . '%')
                ->orWhere('score', 'like', '%' . $search . '%');
            });
        }

    }
}
