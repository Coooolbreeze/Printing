<?php

use Illuminate\Database\Seeder;

class SecondaryTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\SecondaryType::saveAll([
            ['id' => 1, 'type_id' => 1, 'name' => '经典商务'],
            ['id' => 2, 'type_id' => 1, 'name' => '精致时尚'],
            ['id' => 3, 'type_id' => 1, 'name' => '极致奢华'],
        ]);
    }
}
