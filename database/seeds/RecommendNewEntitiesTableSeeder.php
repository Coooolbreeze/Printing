<?php

use Illuminate\Database\Seeder;

class RecommendNewEntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\RecommendNewEntity::saveAll([
            ['id' => 1, 'image_id' => null, 'url' => null],
            ['id' => 2, 'image_id' => null, 'url' => null],
            ['id' => 3, 'image_id' => null, 'url' => null],
        ]);
    }
}
