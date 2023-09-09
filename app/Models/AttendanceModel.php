<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceModel extends Model
{
    use HasFactory;

    // Surrender Details to Students Model
    public function students()
    {
        return $this->belongsTo(register_student::class);
    }


    protected $table = 'attendance';

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'term',
        'session',
        'time',

        
    ];
}
