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
        Schema::create('curriculum', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('class');
            $table->string('sura')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('times')->nullable();
            $table->string('bita')->nullable();
            $table->string('grade')->nullable();
            $table->string('hadda')->nullable();
            $table->string('teacher')->nullable();
            $table->string('set')->nullable();
            $table->longText('comment')->nullable();
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
        Schema::dropIfExists('curriculum');
    }
};
