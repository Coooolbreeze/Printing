<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entity_id');
            $table->decimal('price')->nullable();
            $table->decimal('weight')->nullable();
            $table->string('combination');
            $table->timestamps();
        });

        DB::update('ALTER TABLE combinations AUTO_INCREMENT = 100000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('combinations');
    }
}
