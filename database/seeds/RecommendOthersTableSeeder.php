<?php

use Illuminate\Database\Seeder;

class RecommendOthersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\RecommendOther::saveAll([
            ['id' => 1, 'entity_id' => null],
            ['id' => 2, 'entity_id' => null],
            ['id' => 3, 'entity_id' => null],
            ['id' => 4, 'entity_id' => null],
        ]);
    }
}
