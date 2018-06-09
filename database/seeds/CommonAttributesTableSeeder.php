<?php

use Illuminate\Database\Seeder;

class CommonAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CommonAttribute::saveAll([
            ['id' => 1, 'category_id' => 1, 'name' => '纸张'],
            ['id' => 2, 'category_id' => 1, 'name' => '尺寸'],
            ['id' => 3, 'category_id' => 1, 'name' => '工艺'],
            ['id' => 4, 'category_id' => 1, 'name' => '数量'],
            ['id' => 5, 'category_id' => 2, 'name' => '纸张'],
            ['id' => 6, 'category_id' => 2, 'name' => '尺寸'],
            ['id' => 7, 'category_id' => 2, 'name' => '数量'],
            ['id' => 8, 'category_id' => 3, 'name' => '纸张'],
            ['id' => 9, 'category_id' => 3, 'name' => '尺寸'],
            ['id' => 10, 'category_id' => 3, 'name' => '数量'],
            ['id' => 11, 'category_id' => 4, 'name' => '纸张'],
            ['id' => 12, 'category_id' => 4, 'name' => '形式'],
            ['id' => 13, 'category_id' => 4, 'name' => '规格'],
            ['id' => 14, 'category_id' => 4, 'name' => '数量'],
        ]);
    }
}
