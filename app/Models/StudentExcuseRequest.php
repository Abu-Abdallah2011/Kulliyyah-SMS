<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExcuseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'guardian_id',
        'type',
        'reason',
        'requested_days',
        'requested_start_time',
        'requested_end_time',
        'requested_latest_time',
        'term',
        'session',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'requested_days' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(register_student::class, 'student_id');
    }

    public function guardian()
    {
        return $this->belongsTo(register_guardian::class, 'guardian_id');
    }

    public function card()
    {
        return $this->hasOne(StudentExcuseCard::class, 'student_excuse_request_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
