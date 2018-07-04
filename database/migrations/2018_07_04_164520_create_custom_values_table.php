<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('custom_attribute_id');
            $table->string('name');
            $table->string('unit');
            $table->decimal('min');
            $table->decimal('max');
            $table->decimal('default');
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
        Schema::dropIfExists('custom_values');
    }
}
