<?php

namespace App\Models;

use App\Support\HaddaCoverageCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'mock_cycle_id',
        'student_id',
        'teacher_id',
        'date',
        'sura',
        'from',
        'to_surah',
        'to',
        'grade',
        'comment',
        'recorded_by_user_id',
        'recorded_by_name',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function cycle()
    {
        return $this->belongsTo(MockCycle::class, 'mock_cycle_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(register_student::class, 'student_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(register_teacher::class, 'teacher_id', 'id');
    }

    public function pageCount(): int
    {
        return (int) HaddaCoverageCalculator::analyze($this)['pages'];
    }
}
