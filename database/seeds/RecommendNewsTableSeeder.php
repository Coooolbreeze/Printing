<?php

use Illuminate\Database\Seeder;

class RecommendNewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\RecommendNews::saveAll([
            ['id' => 1, 'category' => '印刷活动', 'title' => null, 'url' => null],
            ['id' => 2, 'category' => '印刷活动', 'title' => null, 'url' => null],
            ['id' => 3, 'category' => '印刷活动', 'title' => null, 'url' => null],
            ['id' => 4, 'category' => '印刷活动', 'title' => null, 'url' => null],
            ['id' => 5, 'category' => '印刷知识', 'title' => null, 'url' => null],
            ['id' => 6, 'category' => '印刷知识', 'title' => null, 'url' => null],
            ['id' => 7, 'category' => '印刷知识', 'title' => null, 'url' => null],
            ['id' => 8, 'category' => '印刷知识', 'title' => null, 'url' => null],
        ]);
    }
}
