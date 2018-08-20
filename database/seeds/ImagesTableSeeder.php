<?php

use Illuminate\Database\Seeder;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Image::saveAll([
            ['id' => 1, 'src' => 'images/769730241348996457.png'],
            ['id' => 2, 'src' => 'images/802332368216711964.png'],
            ['id' => 3, 'src' => 'images/611967575482199341.png'],
            ['id' => 4, 'src' => 'images/571231049126975433.png'],
            ['id' => 5, 'src' => 'images/321461499502026884.png'],
            ['id' => 6, 'src' => 'images/400504710452008183.png'],
            ['id' => 7, 'src' => 'images/81738944608645449.png'],
            ['id' => 8, 'src' => 'images/528667457085723330.png'],
            ['id' => 9, 'src' => 'images/81365286473349625.png'],
            ['id' => 10, 'src' => 'images/717593381307162829.png'],
        ]);
    }
}
