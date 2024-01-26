<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE exams CHANGE `1st_ca` first_ca varchar(255)');
        DB::statement('ALTER TABLE exams CHANGE `2nd_ca` second_ca varchar(255)');
        DB::statement('ALTER TABLE exams CHANGE `3rd_ca` third_ca varchar(255)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            //
        });
    }
};
