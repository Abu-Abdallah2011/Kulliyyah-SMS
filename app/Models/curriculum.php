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

    public function scopeOrderByCurriculumDate($query, string $direction = 'desc')
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
                ->orWhere('sura', 'like', '%' . $search . '%')
                ->orWhere('from', 'like', '%' . $search . '%')
                ->orWhere('to', 'like', '%' . $search . '%')
                ->orWhere('times', 'like', '%' . $search . '%')
                ->orWhere('bita', 'like', '%' . $search . '%')
                ->orWhere('grade', 'like', '%' . $search . '%')
                ->orWhere('hadda', 'like', '%' . $search . '%')
                ->orWhere('comment', 'like', '%' . $search . '%')
                ->orWhere('teacher', 'like', '%' . $search . '%')
                ->orWhere('set', 'like', '%' . $search . '%');
            });
        }
    }
}
