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
        Schema::create('school_fees_database', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('student_id');
            $table->string('date')->nullable();
            $table->string('status');
            $table->string('amount')->nullable();
            $table->string('term');
            $table->string('session');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_fees_database');
    }
};
