<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_applies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no');
            $table->integer('user_id');
            $table->decimal('price');
            $table->tinyInteger('type');
            $table->tinyInteger('status')->default(0)->comment('0未处理 1已同意 2已驳回');
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
        Schema::dropIfExists('order_applies');
    }
}
