<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_excuse_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students_details_tables')->cascadeOnDelete();
            $table->foreignId('guardian_id')->constrained('guardians_details')->cascadeOnDelete();
            $table->string('type');
            $table->text('reason');
            $table->json('requested_days')->nullable();
            $table->time('requested_start_time')->nullable();
            $table->time('requested_end_time')->nullable();
            $table->time('requested_latest_time')->nullable();
            $table->string('term')->nullable();
            $table->string('session')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_excuse_requests');
    }
};
