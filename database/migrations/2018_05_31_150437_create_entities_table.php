<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->nullable();
            $table->integer('secondary_type_id')->nullable();
            $table->string('name');
            $table->string('summary');
            $table->longText('body');
            $table->tinyInteger('lead_time');
            $table->tinyInteger('custom_number')->default(0)->comment('0不允许自定义 1单人数量 2多人数量');
            $table->string('unit')->nullable();
            $table->string('title');
            $table->string('keywords');
            $table->string('describe')->nullable();
            $table->integer('sales')->default(0);
            $table->tinyInteger('status')->default(1)->comment('0未发布 1销售中 2已下架');
            $table->timestamps();
        });

        DB::update('ALTER TABLE entities AUTO_INCREMENT = 100000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entities');
    }
}
