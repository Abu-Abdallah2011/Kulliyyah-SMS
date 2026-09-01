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
        Schema::create('allowance_voucher_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('allowance_vouchers')->onDelete('cascade');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('teacher_fullname');
            $table->string('bank_branch')->nullable();
            $table->string('acct_name')->nullable();
            $table->string('acct_no')->nullable();
            $table->decimal('monthly_allowance', 12, 2)->default(0);
            $table->boolean('included')->default(true);
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('allowance_voucher_items');
    }
};

