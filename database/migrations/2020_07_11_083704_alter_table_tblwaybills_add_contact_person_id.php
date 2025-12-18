<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTblwaybillsAddContactPersonId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblwaybills', function (Blueprint $table) {
            $table->bigInteger('contact_person_id')->nullable($value=true)->unsigned()->after('is_guest');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblwaybills', function (Blueprint $table) {
            $table->dropForeign('contact_person_id');
            
        });
    }
}
