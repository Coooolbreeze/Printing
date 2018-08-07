<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLargeCategoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('large_category_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('large_category_id');
            $table->integer('item_id');
            $table->integer('item_type')->default(1)->comment('1类型 2商品');
            $table->tinyInteger('is_hot')->default(0);
            $table->tinyInteger('is_new')->default(0);
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
        Schema::dropIfExists('large_category_items');
    }
}
