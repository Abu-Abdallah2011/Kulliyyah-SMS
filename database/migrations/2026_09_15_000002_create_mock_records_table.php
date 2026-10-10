<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mock_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mock_cycle_id')->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('teacher_id')->index();
            $table->date('date')->nullable()->index();
            $table->string('sura')->nullable();
            $table->string('from')->nullable();
            $table->string('to_surah')->nullable();
            $table->string('to')->nullable();
            $table->string('grade')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('recorded_by_user_id')->nullable();
            $table->string('recorded_by_name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mock_records');
    }
};
