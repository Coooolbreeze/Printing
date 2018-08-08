<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('order_no');
            $table->decimal('price');
            $table->tinyInteger('is_paid')->default(0);
            $table->tinyInteger('pay_type')->comment('1支付宝 2微信');
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
        Schema::dropIfExists('recharge_orders');
    }
}
