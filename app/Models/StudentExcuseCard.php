<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExcuseCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_excuse_request_id',
        'student_id',
        'guardian_id',
        'card_number',
        'qr_token',
        'type',
        'valid_days',
        'start_time',
        'end_time',
        'latest_time',
        'term',
        'session',
        'valid_from',
        'valid_until',
        'status',
        'approved_by',
        'approved_at',
        'revoked_at',
        'revoked_by',
        'revocation_reason',
    ];

    protected $casts = [
        'valid_days' => 'array',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'approved_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(StudentExcuseRequest::class, 'student_excuse_request_id');
    }

    public function student()
    {
        return $this->belongsTo(register_student::class, 'student_id');
    }

    public function guardian()
    {
        return $this->belongsTo(register_guardian::class, 'guardian_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function revoker()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function approverDisplayName(): string
    {
        return $this->approver?->teachers?->fullname
            ?? $this->approver?->username
            ?? $this->approver?->email
            ?? 'N/A';
    }

    public function verificationStatus(?Carbon $now = null): array
    {
        $now = $now ?: now();

        if ($this->status === 'revoked') {
            return ['label' => 'Revoked', 'valid' => false, 'message' => 'This excuse card has been revoked.'];
        }

        if ($this->status !== 'active') {
            return ['label' => 'Inactive', 'valid' => false, 'message' => 'This excuse card is not currently active.'];
        }

        if ($this->valid_from && $now->toDateString() < $this->valid_from->toDateString()) {
            return ['label' => 'Not Yet Valid', 'valid' => false, 'message' => 'This card is not yet active.'];
        }

        if ($this->valid_until && $now->toDateString() > $this->valid_until->toDateString()) {
            return ['label' => 'Expired', 'valid' => false, 'message' => 'This card has expired.'];
        }

        $today = $now->format('l');
        $validDays = $this->valid_days ?: [];

        if ($validDays && !in_array($today, $validDays, true)) {
            return ['label' => 'Invalid Day', 'valid' => false, 'message' => 'This card is not valid on ' . $today . '.'];
        }

        $currentTime = $now->format('H:i:s');

        if ($this->type === 'late' && $this->latest_time && $currentTime > $this->latest_time) {
            return ['label' => 'Outside Time', 'valid' => false, 'message' => 'The approved late-arrival time has passed.'];
        }

        if ($this->type === 'out' && (($this->start_time && $currentTime < $this->start_time) || ($this->end_time && $currentTime > $this->end_time))) {
            return ['label' => 'Outside Time', 'valid' => false, 'message' => 'This card is only valid during the approved out-excuse time.'];
        }

        return ['label' => 'Valid', 'valid' => true, 'message' => 'This excuse card is currently valid.'];
    }
}
