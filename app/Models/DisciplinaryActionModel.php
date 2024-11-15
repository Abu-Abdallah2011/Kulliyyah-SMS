<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaryActionModel extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(register_student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(register_teacher::class);
    }

    protected $table = 'disciplinary_actions';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'title',
        'description',
        'action_taken',
        'school_issued_document',
        'offender_issued_document',
        'status',
        'added_by',
        'edited_by',
    ];
}
