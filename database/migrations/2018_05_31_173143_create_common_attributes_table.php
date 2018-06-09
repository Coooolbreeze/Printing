<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('name');
            $table->tinyInteger('is_multiple')->default(0)->common('0否 1是');
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
        Schema::dropIfExists('common_attributes');
    }
}
