<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('coupon_id');
            $table->string('coupon_no');
            $table->string('name');
            $table->tinyInteger('type')->default(1)->comment('1满减 2抵扣');
            $table->integer('quota');
            $table->integer('satisfy')->nullable();
            $table->tinyInteger('is_meanwhile')->default(0);
            $table->tinyInteger('is_used')->default(0);
            $table->tinyInteger('is_disabled')->default(0);
            $table->timestamp('finished_at');
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
        Schema::dropIfExists('user_coupons');
    }
}
