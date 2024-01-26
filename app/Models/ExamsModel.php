<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamsModel extends Model
{
    use HasFactory;

    // Surrender Details to Students Model
    public function students()
    {
        return $this->belongsTo(register_student::class);
    }

    // Surrender Details to Subjects Model
    public function subjects()
    {
        return $this->belongsTo(subjectsModel::class);
    }


    protected $table = 'exams';

    protected $fillable = [
        'student_id',
        'subject_id',
        'first_ca',
        'second_ca',
        'third_ca',
        'exams',
        'term',
        'session',
        'comment',

        
    ];
}
