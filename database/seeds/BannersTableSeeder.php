<?php

use Illuminate\Database\Seeder;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Banner::saveAll([
            ['id' => 1, 'image_id' => null, 'url' => null],
            ['id' => 2, 'image_id' => null, 'url' => null],
            ['id' => 3, 'image_id' => null, 'url' => null],
            ['id' => 4, 'image_id' => null, 'url' => null],
            ['id' => 5, 'image_id' => null, 'url' => null]
        ]);
    }
}
