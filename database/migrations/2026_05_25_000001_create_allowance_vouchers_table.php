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
        Schema::create('allowance_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique();
            $table->string('voucher_month');
            $table->unsignedSmallInteger('voucher_year');
            $table->string('status')->default('draft');
            $table->unsignedInteger('teacher_count')->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->unsignedBigInteger('generated_by_user_id')->nullable();
            $table->string('generated_by_name')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allowance_vouchers');
    }
};

