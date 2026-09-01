<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceVoucherItem extends Model
{
    use HasFactory;

    protected $table = 'allowance_voucher_items';

    protected $fillable = [
        'voucher_id',
        'teacher_id',
        'teacher_fullname',
        'bank_branch',
        'acct_name',
        'acct_no',
        'base_allowance',
        'deduction_amount',
        'bonus_amount',
        'monthly_allowance',
        'attendance_total',
        'attendance_present',
        'attendance_late',
        'attendance_percentage',
        'attendance_eligible',
        'included',
        'reason',
    ];

    protected $casts = [
        'base_allowance' => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'monthly_allowance' => 'decimal:2',
        'attendance_percentage' => 'decimal:2',
        'attendance_eligible' => 'boolean',
        'included' => 'boolean',
    ];

    public function voucher()
    {
        return $this->belongsTo(AllowanceVoucher::class, 'voucher_id', 'id');
    }
}
