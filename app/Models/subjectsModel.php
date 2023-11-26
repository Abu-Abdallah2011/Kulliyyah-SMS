<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subjectsModel extends Model
{
    use HasFactory;

    //  Link this model to Exams model
    public function exams()
    {
       return $this->hasMany(ExamsModel::class, 'subject_id', 'subject_id');
    }

    protected $table = 'subjects';

    protected $fillable = [
        'subject',
        'marks_obtainable',
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false) {
            $query->where('subject', 'like', '%' . request('search') . '%');
            $query->where('category', 'like', '%' . request('search') . '%');
        }
    }
}
