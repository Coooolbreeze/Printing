<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::saveAll([
            ['id' => 1, 'large_category_id' => 1, 'name' => '经典商务类'],
            ['id' => 2, 'large_category_id' => 1, 'name' => '精致时尚类'],
            ['id' => 3, 'large_category_id' => 1, 'name' => '极致奢华类'],
            ['id' => 4, 'large_category_id' => 2, 'name' => '印刷类'],
            ['id' => 5, 'large_category_id' => 2, 'name' => '制造类'],
            ['id' => 6, 'large_category_id' => 3, 'name' => '印刷类'],
            ['id' => 7, 'large_category_id' => 3, 'name' => '制造类'],
            ['id' => 8, 'large_category_id' => 4, 'name' => '印刷类'],
            ['id' => 9, 'large_category_id' => 4, 'name' => '制造类'],
            ['id' => 10, 'large_category_id' => 5, 'name' => '印刷类'],
            ['id' => 11, 'large_category_id' => 5, 'name' => '制造类'],
        ]);
    }
}
