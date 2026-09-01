<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_excuse_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_excuse_request_id')->constrained('student_excuse_requests')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students_details_tables')->cascadeOnDelete();
            $table->foreignId('guardian_id')->constrained('guardians_details')->cascadeOnDelete();
            $table->string('card_number')->unique();
            $table->string('qr_token', 80)->unique();
            $table->string('type');
            $table->json('valid_days')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->time('latest_time')->nullable();
            $table->string('term')->nullable();
            $table->string('session')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->foreignId('revoked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('revocation_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_excuse_cards');
    }
};
