<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register_student extends Model
{
    use HasFactory;

    public const HADDA_STATUS_ACTIVE = 'active_memorization';
    public const HADDA_STATUS_MOCK = 'mock_exam';
    public const HADDA_STATUS_GRADUATED = 'graduated';
    public const HADDA_STATUS_NOT_APPLICABLE = 'not_applicable';

    public const TARTEEL_STATUS_ACTIVE = 'active';
    public const TARTEEL_STATUS_GRADUATED = 'graduated';
    public const TARTEEL_STATUS_NOT_APPLICABLE = 'not_applicable';


    // Surrender Details to Teachers Model
    public function teacher()
    {
        return $this->belongsTo(register_teacher::class);
    }

     // Surrender Details to Guardians Model
     public function guardian()
     {
         return $this->belongsTo(register_guardian::class);
     }

    //  Link this model to curriculum model
    public function curriculum()
     {
        return $this->hasMany(curriculum::class, 'set', 'set');
     }

     //  Link this model to Hadda model
    public function Hadda()
    {
       return $this->hasMany(Hadda::class, 'student_id', 'student_id');
    }

    //  Link this model to Attendance model
    public function attendance()
    {
       return $this->hasMany(AttendanceModel::class, 'student_id', 'id');
    }

    //  Link this model to Exams model
    public function exams()
    {
       return $this->hasMany(ExamsModel::class, 'student_id', 'id');
    }

    //  Link this model to School Fees Model
    public function fees()
    {
        return $this->hasMany(SchoolFeesModel::class, 'student_id', 'id');
    }

    //  Link this model to Students Disciplinary action model
    public function disciplinaryActions()
    {
       return $this->hasMany(DisciplinaryActionModel::class, 'student_id', 'id');
    }

    //  Link this model to Excuses model
    public function excuses()
    {
       return $this->hasMany(excusesModel::class, 'student_id', 'id');
    }

    protected $table = 'students_details_tables';

    protected $fillable = [
        'fullname',
        'class',
        'gender',
        'dob',
        'doa',
        'reg_fee',
        'address',
        'status',
        'hadda_status',
        'tarteel_status',
        'guardian_id',
        // 'grad_type',
        // 'mock_fee',
        // 'grad_fee',
        // 'grad_date',
        // 'grad_yr',
        'photo',
        'relationship',
        'set',
        'created_by',
        'edited_by',
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('fullname', 'like', '%' . request('search') . '%')
            ->orWhere('class', 'like', '%' . request('search') . '%')
            ->orWhere('dob', 'like', '%' . request('search') . '%')
            ->orWhere('doa', 'like', '%' . request('search') . '%')
            ->orWhere('reg_fee', 'like', '%' . request('search') . '%')
            ->orWhere('address', 'like', '%' . request('search') . '%')
            ->orWhere('status', 'like', '%' . request('search') . '%')
            ->orWhere('hadda_status', 'like', '%' . request('search') . '%')
            ->orWhere('tarteel_status', 'like', '%' . request('search') . '%')
            ->orWhere('guardian_id', 'like', '%' . request('search') . '%')
            ->orWhere('gender', 'like', '%' . request('search') . '%')
            ->orWhere('relationship', 'like', '%' . request('search') . '%')
            ->orWhere('id', 'like', '%' . request('search') . '%')
            ->orWhere('set', 'like', '%' . request('search') . '%');
        }
    }

    public function scopeCurrentlyEnrolled($query)
    {
        return $query->where(function ($query) {
            $query->where('status', 'IN SCHOOL')
                ->orWhere(function ($query) {
                    $query->where('status', 'GRADUATE')
                        ->where('grad_type', 'TARTEEL ZALLA')
                        ->where('hadda_status', self::HADDA_STATUS_ACTIVE);
                });
        });
    }

    public function scopeCoverageLeaderboardEligible($query)
    {
        return $query
            ->where(function ($query) {
                $query->where('status', 'IN SCHOOL')
                    ->orWhere('grad_type', 'TARTEEL ZALLA');
            })
            ->where(function ($query) {
                $query->where('hadda_status', self::HADDA_STATUS_ACTIVE)
                    ->orWhere('tarteel_status', self::TARTEEL_STATUS_ACTIVE);
            });
    }

    public function isCoverageLeaderboardEligible(): bool
    {
        $hasActiveSchoolStatus = $this->status === 'IN SCHOOL' || $this->grad_type === 'TARTEEL ZALLA';
        $hasActiveTrack = $this->hadda_status === self::HADDA_STATUS_ACTIVE
            || $this->tarteel_status === self::TARTEEL_STATUS_ACTIVE;

        return $hasActiveSchoolStatus && $hasActiveTrack;
    }

    public static function haddaStatusOptions(): array
    {
        return [
            self::HADDA_STATUS_ACTIVE => 'Active Memorization',
            self::HADDA_STATUS_MOCK => 'Mock Exam',
            self::HADDA_STATUS_GRADUATED => 'Graduated',
            self::HADDA_STATUS_NOT_APPLICABLE => 'Not Applicable',
        ];
    }

    public static function tarteelStatusOptions(): array
    {
        return [
            self::TARTEEL_STATUS_ACTIVE => 'Active',
            self::TARTEEL_STATUS_GRADUATED => 'Graduated',
            self::TARTEEL_STATUS_NOT_APPLICABLE => 'Not Applicable',
        ];
    }

    public function haddaStatusLabel(): string
    {
        return self::haddaStatusOptions()[$this->hadda_status] ?? 'Active Memorization';
    }

    public function tarteelStatusLabel(): string
    {
        return self::tarteelStatusOptions()[$this->tarteel_status] ?? 'Not Applicable';
    }
}
