<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceVoucher extends Model
{
    use HasFactory;

    protected $table = 'allowance_vouchers';

    protected $fillable = [
        'voucher_no',
        'voucher_month',
        'voucher_year',
        'status',
        'teacher_count',
        'total_amount',
        'generated_by_user_id',
        'generated_by_name',
        'finalized_at',
        'pdf_path',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(AllowanceVoucherItem::class, 'voucher_id', 'id');
    }
}

