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
            ['id' => 1, 'name' => '名片'],
            ['id' => 2, 'name' => '信纸'],
            ['id' => 3, 'name' => '便签'],
            ['id' => 4, 'name' => '信封'],
            ['id' => 5, 'name' => '⽆碳复写'],
            ['id' => 6, 'name' => '⽂件袋'],
            ['id' => 7, 'name' => '挂画'],
            ['id' => 8, 'name' => '光盘'],
            ['id' => 9, 'name' => '台历'],
            ['id' => 10, 'name' => '纸杯'],
            ['id' => 11, 'name' => '宣传单'],
            ['id' => 12, 'name' => '封套'],
            ['id' => 13, 'name' => '手提袋'],
            ['id' => 14, 'name' => '代金券'],
            ['id' => 15, 'name' => '不干胶贴'],
            ['id' => 16, 'name' => '海报'],
            ['id' => 17, 'name' => '⼴告扇'],
            ['id' => 18, 'name' => 'x展架'],
            ['id' => 19, 'name' => '易拉宝'],
            ['id' => 20, 'name' => '⻔型展架'],
            ['id' => 21, 'name' => '写真画面'],
            ['id' => 22, 'name' => 'kt板写真'],
            ['id' => 23, 'name' => '条幅'],
            ['id' => 24, 'name' => '⽆纺布袋'],
            ['id' => 25, 'name' => '画册'],
            ['id' => 26, 'name' => '培训手册'],
            ['id' => 27, 'name' => '员⼯手册'],
            ['id' => 28, 'name' => '折页'],
        ]);
    }
}
