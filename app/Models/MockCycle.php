<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockCycle extends Model
{
    use HasFactory;

    public const TYPE_PRE_MOCK = 'pre_mock';
    public const TYPE_MOCK = 'mock';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'cycle_type',
        'session',
        'term',
        'start_date',
        'target_end_date',
        'completed_at',
        'status',
        'notes',
        'created_by_user_id',
        'created_by_name',
    ];

    protected $casts = [
        'start_date' => 'date',
        'target_end_date' => 'date',
        'completed_at' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(register_student::class, 'student_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(register_teacher::class, 'teacher_id', 'id');
    }

    public function records()
    {
        return $this->hasMany(MockRecord::class, 'mock_cycle_id', 'id');
    }

    public static function typeOptions(): array
    {
        return [
            self::TYPE_PRE_MOCK => 'Pre-Mock',
            self::TYPE_MOCK => 'Mock',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function typeLabel(): string
    {
        return self::typeOptions()[$this->cycle_type] ?? 'Mock';
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Active';
    }
}
