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
        Schema::create('disciplinary_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('action_taken');
            $table->string('school_issued_document')->nullable(); 
            $table->string('offender_issued_document')->nullable(); 
            $table->enum('status', ['pending', 'resolved'])->default('pending');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('students_details_tables')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disciplinary_actions');
    }
};
