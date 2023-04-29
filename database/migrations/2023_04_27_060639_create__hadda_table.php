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
        Schema::create('_hadda', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('class');
            $table->string('name');
            $table->string('sura');
            $table->string('from');
            $table->string('to');
            $table->string('grade');
            $table->string('comment')->nullable();
            $table->string('teacher');
            $table->string('term');
            $table->string('session');
            $table->string('student_id')->nullable();
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
        Schema::dropIfExists('_hadda');
    }
};
