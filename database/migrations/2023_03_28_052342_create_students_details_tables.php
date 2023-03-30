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
        Schema::create('students_details_tables', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('class');
            $table->string('gender');
            $table->string('dob');
            $table->string('doa');
            $table->string('reg_fee');
            $table->string('address');
            $table->string('status');
            $table->string('grad_type')->nullable();
            $table->string('mock_fee')->nullable();
            $table->string('grad_date')->nullable();
            $table->string('grad_yr')->nullable();
            $table->string('photo')->nullable();
            $table->string('guardian_id');
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
        Schema::dropIfExists('students_details_tables');
    }
};
