<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('order_no')->nullable();
            $table->decimal('number');
            $table->decimal('surplus');
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
        Schema::dropIfExists('balance_records');
    }
}
