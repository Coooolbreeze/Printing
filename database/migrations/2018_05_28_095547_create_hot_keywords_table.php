<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->tinyInteger('sort');
            $table->tinyInteger('status')->default(1)->commnet('0未发布 1已发布');
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
        Schema::dropIfExists('hot_keywords');
    }
}
