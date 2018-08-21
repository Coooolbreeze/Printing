<?php

use Illuminate\Database\Seeder;

class NewsCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\NewsCategory::saveAll([
            ['id' => 1, 'title' => '印刷知识'],
            ['id' => 2, 'title' => '印刷头条'],
            ['id' => 3, 'title' => '印刷活动']
        ]);
    }
}
