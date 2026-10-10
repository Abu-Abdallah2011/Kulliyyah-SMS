<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mock_cycles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('teacher_id')->index();
            $table->string('cycle_type')->default('mock');
            $table->string('session')->nullable()->index();
            $table->string('term')->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('target_end_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->string('status')->default('active')->index();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->string('created_by_name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mock_cycles');
    }
};
