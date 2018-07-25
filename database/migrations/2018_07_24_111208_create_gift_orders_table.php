<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('gift_id');
            $table->text('snap_content');
            $table->text('snap_address');
            $table->string('tracking_no')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1未发货 2已发货');
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
        Schema::dropIfExists('gift_orders');
    }
}
