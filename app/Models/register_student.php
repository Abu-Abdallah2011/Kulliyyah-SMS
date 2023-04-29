<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register_student extends Model
{
    use HasFactory;


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

    //  Surrender this model to curriculum model
    public function curriculum()
     {
        return $this->hasMany(curriculum::class, 'set', 'set');
     }

     //  Surrender this model to Hadda model
    public function Hadda()
    {
       return $this->hasMany(Hadda::class, 'student_id', 'student_id');
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
        'guardian_id',
        // 'grad_type',
        // 'mock_fee',
        // 'grad_fee',
        // 'grad_date',
        // 'grad_yr',
        'photo',
        'relationship',
        'set',
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
            ->orWhere('guardian_id', 'like', '%' . request('search') . '%')
            ->orWhere('gender', 'like', '%' . request('search') . '%')
            ->orWhere('relationship', 'like', '%' . request('search') . '%')
            ->orWhere('id', 'like', '%' . request('search') . '%')
            ->orWhere('set', 'like', '%' . request('search') . '%');
        }
    }
}
