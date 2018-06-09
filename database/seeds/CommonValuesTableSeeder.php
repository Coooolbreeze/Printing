<?php

use Illuminate\Database\Seeder;

class CommonValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CommonValue::saveAll([
            ['common_attribute_id' => 1, 'name' => '300g铜版纸'],
            ['common_attribute_id' => 2, 'name' => '90x54mm'],
            ['common_attribute_id' => 2, 'name' => '90x50mm'],
            ['common_attribute_id' => 2, 'name' => '90x45mm'],
            ['common_attribute_id' => 3, 'name' => '覆哑膜'],
            ['common_attribute_id' => 3, 'name' => '单面uv'],
            ['common_attribute_id' => 3, 'name' => '双面uv'],
            ['common_attribute_id' => 3, 'name' => '烫金'],
            ['common_attribute_id' => 3, 'name' => '烫银'],
            ['common_attribute_id' => 4, 'name' => '2盒'],
            ['common_attribute_id' => 4, 'name' => '5盒'],
            ['common_attribute_id' => 4, 'name' => '10盒'],
            ['common_attribute_id' => 4, 'name' => '20盒'],
            ['common_attribute_id' => 4, 'name' => '50盒'],
            ['common_attribute_id' => 4, 'name' => '100盒'],
            ['common_attribute_id' => 5, 'name' => '80g双胶纸'],
            ['common_attribute_id' => 6, 'name' => '(A5)210*140mm'],
            ['common_attribute_id' => 6, 'name' => '(A4)210*285mm'],
            ['common_attribute_id' => 7, 'name' => '50'],
            ['common_attribute_id' => 7, 'name' => '100'],
            ['common_attribute_id' => 7, 'name' => '200'],
            ['common_attribute_id' => 7, 'name' => '500'],
            ['common_attribute_id' => 7, 'name' => '1000'],
            ['common_attribute_id' => 8, 'name' => '80g双胶纸'],
            ['common_attribute_id' => 9, 'name' => '100*140mm'],
            ['common_attribute_id' => 10, 'name' => '50本'],
            ['common_attribute_id' => 10, 'name' => '100本'],
            ['common_attribute_id' => 10, 'name' => '200本'],
            ['common_attribute_id' => 10, 'name' => '500本'],
            ['common_attribute_id' => 10, 'name' => '1000本'],
            ['common_attribute_id' => 11, 'name' => '120g双胶纸'],
            ['common_attribute_id' => 11, 'name' => '140g双胶纸'],
            ['common_attribute_id' => 11, 'name' => '180g双胶纸'],
            ['common_attribute_id' => 12, 'name' => '中式侧开口'],
            ['common_attribute_id' => 12, 'name' => '西式上开口'],
            ['common_attribute_id' => 13, 'name' => '2号信封(176*110mm)'],
            ['common_attribute_id' => 13, 'name' => '5号信封(220*110mm)'],
            ['common_attribute_id' => 13, 'name' => '7号信封(229*162mm)'],
            ['common_attribute_id' => 13, 'name' => '9号信封(324*229mm)'],
            ['common_attribute_id' => 14, 'name' => '500'],
            ['common_attribute_id' => 14, 'name' => '1000'],
            ['common_attribute_id' => 14, 'name' => '2000'],
            ['common_attribute_id' => 14, 'name' => '5000'],
            ['common_attribute_id' => 14, 'name' => '10000'],
        ]);
    }
}
