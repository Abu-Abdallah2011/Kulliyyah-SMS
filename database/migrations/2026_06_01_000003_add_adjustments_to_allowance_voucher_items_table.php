<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('allowance_voucher_items', function (Blueprint $table) {
            $table->decimal('base_allowance', 12, 2)->default(0)->after('acct_no');
            $table->decimal('deduction_amount', 12, 2)->default(0)->after('base_allowance');
            $table->decimal('bonus_amount', 12, 2)->default(0)->after('deduction_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allowance_voucher_items', function (Blueprint $table) {
            $table->dropColumn(['base_allowance', 'deduction_amount', 'bonus_amount']);
        });
    }
};

