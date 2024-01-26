<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register_teacher extends Model
{
    use HasFactory;

    // Surrender Details to Users Model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Linking each Teacher to his Students
    public function students()
{
    return $this->hasMany(register_student::class, 'class', 'class');
}

    // Linking each Teacher to his Guardian Details if any
    public function guardian()
{
    return $this->hasOne(register_guardian::class, 'teacher_id', 'teacher_id');
}

    // Linking this model to Curriculum Model
    public function curriculum()
    {
        return $this->hasMany(curriculum::class, 'set', 'set');
    }

    //  Link this model to Teachers Attendance model
    public function attendance()
    {
       return $this->hasMany(teachersAttendanceModel::class, 'teacher_id', 'id');
    }


    protected $table = 'teachers_details';

    protected $fillable = [
        'user_id',
        'fullname',
        'class',
        'gender',
        'dob',
        'marital_status',
        'dofa',
        'address',
        'status',
        'rank',
        'promotion_yr',
        'contact_no',
        'bank_branch',
        'acct_name',
        'acct_no',
        'allowance',
        'hometown',
        'nok',
        'relationship',
        'contact',
        'photo',
        'set',
        'created_by',
        'edited_by',
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query
            ->where('user_id', 'like', '%' . request('search') . '%')
            ->orwhere('fullname', 'like', '%' . request('search') . '%')
            ->orWhere('class', 'like', '%' . request('search') . '%')
            ->orWhere('gender', 'like', '%' . request('search') . '%')
            ->orWhere('dob', 'like', '%' . request('search') . '%')
            ->orWhere('marital_status', 'like', '%' . request('search') . '%')
            ->orWhere('dofa', 'like', '%' . request('search') . '%')
            ->orWhere('address', 'like', '%' . request('search') . '%')
            ->orWhere('status', 'like', '%' . request('search') . '%')
            ->orWhere('rank', 'like', '%' . request('search') . '%')
            ->orWhere('promotion_yr', 'like', '%' . request('search') . '%')
            ->orWhere('contact_no', 'like', '%' . request('search') . '%')
            ->orWhere('bank_branch', 'like', '%' . request('search') . '%')
            ->orWhere('acct_name', 'like', '%' . request('search') . '%')
            ->orWhere('acct_no', 'like', '%' . request('search') . '%')
            ->orWhere('allowance', 'like', '%' . request('search') . '%')
            ->orWhere('hometown', 'like', '%' . request('search') . '%')
            ->orWhere('nok', 'like', '%' . request('search') . '%')
            ->orWhere('relationship', 'like', '%' . request('search') . '%')
            ->orWhere('contact', 'like', '%' . request('search') . '%')
            ->orWhere('set', 'like', '%' . request('search') . '%');
        }
    }
}
