<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

    // Get Details from Teachers Database
    public function teachers() 
    {
        return $this->hasOne(register_teacher::class);
    }


    // Get Details from Guardians Database
    public function guardians() 
    {
        return $this->hasOne(register_guardian::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'role',
        'email',
        'password',
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('email', 'like', '%' . request('search') . '%')
            ->orWhere('username', 'like', '%' . request('search') . '%')
            ->orWhere('role', 'like', '%' . request('search') . '%')
            ->orWhere('id', 'like', '%' . request('search') . '%');
        }
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
