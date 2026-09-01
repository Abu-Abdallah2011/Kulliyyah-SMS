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
            $table->unsignedInteger('attendance_total')->default(0)->after('monthly_allowance');
            $table->unsignedInteger('attendance_present')->default(0)->after('attendance_total');
            $table->unsignedInteger('attendance_late')->default(0)->after('attendance_present');
            $table->decimal('attendance_percentage', 5, 2)->default(0)->after('attendance_late');
            $table->boolean('attendance_eligible')->default(false)->after('attendance_percentage');
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
            $table->dropColumn([
                'attendance_total',
                'attendance_present',
                'attendance_late',
                'attendance_percentage',
                'attendance_eligible',
            ]);
        });
    }
};
