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
            $table->integer('receipt_id')->nullable();
            $table->string('title');
            $table->text('snap_content');
            $table->text('snap_address');
            $table->decimal('goods_price');
            $table->integer('goods_count');
            $table->decimal('total_weight');
            $table->decimal('freight');
            $table->string('coupon_no')->nullable();
            $table->decimal('discount_amount')->default(0);
            $table->decimal('member_discount')->default(0);
            $table->decimal('balance_deducted')->default(0);
            $table->decimal('total_price');
            $table->string('remark')->nullable();
            $table->string('prepay_id')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0已失效 1未支付 2已支付 3待发货 4已发货 5已收货 6已评论 7未通过');
            $table->tinyInteger('pay_type')->nullable()->comment('1支付宝 2微信 3余额 4后台代付');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('audited_at')->nullable();
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
