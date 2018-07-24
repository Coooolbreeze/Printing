<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccumulatePointsRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accumulate_points_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('number');
            $table->integer('surplus');
            $table->string('describe');
            $table->tinyInteger('type')->default(1)->comment('1收入 2支出');
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
        Schema::dropIfExists('accumulate_points_records');
    }
}
