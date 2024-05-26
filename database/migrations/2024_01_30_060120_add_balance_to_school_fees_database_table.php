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
        Schema::table('school_fees_database', function (Blueprint $table) {
            $table->string('balance')->nullable();
            $table->string('paid_for')->nullable();
            $table->string('description')->nullable();
            $table->string('class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_fees_database', function (Blueprint $table) {
            //
        });
    }
};
