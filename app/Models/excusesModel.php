<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class excusesModel extends Model
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

    protected $table = 'excuses';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'supporting_document',
        'added_by',
        'edited_by',
    ];
}
