<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teachersAttendanceModel extends Model
{
    use HasFactory;

        // Surrender Details to Students Model
        public function students()
        {
            return $this->belongsTo(register_teacher::class);
        }
    
    
        protected $table = 'teachers_attendance';
    
        protected $fillable = [
            'teacher_id',
            'date',
            'time',
            'status',
            'time_in',
            'time_out',
            'term',
            'session',
            
        ];
}
