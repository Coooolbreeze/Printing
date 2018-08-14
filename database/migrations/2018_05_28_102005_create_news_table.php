<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('news_category_id');
            $table->string('image_id');
            $table->string('title');
            $table->string('from');
            $table->string('summary');
            $table->longText('body');
            $table->tinyInteger('sort')->default(0);
            $table->tinyInteger('status')->default(1)->commnet('0未发布 1已发布 2首页推荐');
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
        Schema::dropIfExists('news');
    }
}
