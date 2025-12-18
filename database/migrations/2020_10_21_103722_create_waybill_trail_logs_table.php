<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaybillTrailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('recordlogs')->create('waybill_trail_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_no',15);
            // $table->foreign('reference_no')->references('reference_no')->on('dailyove_online_site.tblwaybills')->onDelete('NO ACTION');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('dailyove_online_site.users')->unsigned();
            $table->string('action_taken');
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
        Schema::dropIfExists('waybill_trail_logs');
    }
}
