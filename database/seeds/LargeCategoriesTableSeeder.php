<?php

use Illuminate\Database\Seeder;

class LargeCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\LargeCategory::saveAll([
            ['id' => 1, 'name' => '名片印刷'],
            ['id' => 2, 'name' => '企业办公'],
            ['id' => 3, 'name' => '营销宣传'],
            ['id' => 4, 'name' => '数码速印'],
        ]);
    }
}
