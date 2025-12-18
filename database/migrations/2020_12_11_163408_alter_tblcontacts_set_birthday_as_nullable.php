<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTblcontactsSetBirthdayAsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dailyove_online_site')->table('tblcontacts', function (Blueprint $table) {
            $table->date('birthday')->nullable($value=true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('dailyove_online_site')->table('tblcontacts', function (Blueprint $table) {
            $table->date('birthday')->nullable($value=false)->change();
        });
    }
}
