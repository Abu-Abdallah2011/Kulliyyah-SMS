<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register_guardian extends Model
{
    use HasFactory;

     // Surrender Guardian Details to Users Model
     public function user()
     {
         return $this->belongsTo(User::class);
     }

     // Surrender Guardian Details to Teachers Model
     public function teacher()
     {
         return $this->belongsTo(register_teacher::class);
     }

     // Linking each Guardian to his Children
     public function students()
     {
         return $this->hasMany(register_student::class, 'guardian_id');
     }

    protected $table = 'guardians_details';

    protected $fillable = [
        'fullname',
        'address',
        'phone',
        'teacher_id',
        'user_id',
        'created_by',
        'edited_by',
        
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('teacher_id', 'like', '%' . request('search') . '%')
            ->orWhere('fullname', 'like', '%' . request('search') . '%')
            ->orWhere('address', 'like', '%' . request('search') . '%')
            ->orWhere('phone', 'like', '%' . request('search') . '%')
            ->orWhere('user_id', 'like', '%' . request('search') . '%')
            ;
        }
    }
}
