<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no');
            $table->integer('user_id');
            $table->string('receipt_id')->nullable();
            $table->text('snap_content');
            $table->text('snap_address');
            $table->decimal('goods_price');
            $table->integer('goods_count');
            $table->decimal('total_weight');
            $table->decimal('freight');
            $table->string('coupon_no')->nullable();
            $table->decimal('discount_amount')->default(0);
            $table->decimal('balance_deducted')->default(0);
            $table->string('remark');
            $table->tinyInteger('status')->default(1)->comment('0已失效 1未支付 2待审核 3待发货 4已发货 5已收货');
            $table->tinyInteger('pay_type')->comment('1支付宝 2微信 3余额');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('received_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
