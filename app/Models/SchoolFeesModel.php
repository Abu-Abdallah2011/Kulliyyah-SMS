<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolFeesModel extends Model
{
    use HasFactory;

    // Surrender Details to Students Model
    public function students()
    {
        return $this->belongsTo(register_student::class);
    }

    protected $table = 'school_fees_database';

    protected $fillable = [
        'student_id',
        'class',
        'date',
        'status',
        'amount',
        'paid_for',
        'balance',
        'term',
        'session',
        'description',
        'added_by',
        'edited_by',
        
    ];
}
