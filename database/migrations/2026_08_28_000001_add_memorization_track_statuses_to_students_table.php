<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students_details_tables', function (Blueprint $table) {
            $table->string('hadda_status')->default('active_memorization')->after('status');
            $table->string('tarteel_status')->default('not_applicable')->after('hadda_status');
        });

        DB::table('students_details_tables')
            ->whereNull('hadda_status')
            ->update(['hadda_status' => 'active_memorization']);

        DB::table('students_details_tables')
            ->whereNull('tarteel_status')
            ->update(['tarteel_status' => 'not_applicable']);
    }

    public function down()
    {
        Schema::table('students_details_tables', function (Blueprint $table) {
            $table->dropColumn(['hadda_status', 'tarteel_status']);
        });
    }
};
