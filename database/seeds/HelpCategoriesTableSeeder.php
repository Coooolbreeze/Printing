<?php

use Illuminate\Database\Seeder;

class HelpCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\HelpCategory::saveAll([
            ['id' => 1, 'name' => '新手指南'],
            ['id' => 2, 'name' => '注意事项'],
            ['id' => 3, 'name' => '订购流程'],
            ['id' => 4, 'name' => '支付说明'],
            ['id' => 5, 'name' => '配送说明'],
            ['id' => 6, 'name' => '优惠券说明'],
            ['id' => 7, 'name' => '账户余额说明'],
        ]);
    }
}
