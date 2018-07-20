<?php

use Illuminate\Database\Seeder;

class MemberLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MemberLevel::saveAll([
            ['name' => '普通会员', 'accumulate_points' => 0, 'discount' => 10],
            ['name' => '铜牌会员', 'accumulate_points' => 1000, 'discount' => 9.9],
            ['name' => '银牌会员', 'accumulate_points' => 2000, 'discount' => 9.5],
            ['name' => '金牌会员', 'accumulate_points' => 3000, 'discount' => 9],
            ['name' => '钻石会员', 'accumulate_points' => 4000, 'discount' => 8.5],
        ]);
    }
}
