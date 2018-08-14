<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Type::saveAll([
            ['id' => 1, 'image_id' => 1, 'name' => '名片'],
            ['id' => 2, 'image_id' => 1, 'name' => '信纸'],
            ['id' => 3, 'image_id' => 1, 'name' => '便签'],
            ['id' => 4, 'image_id' => 1, 'name' => '信封'],
            ['id' => 5, 'image_id' => 1, 'name' => '⽆碳复写'],
            ['id' => 6, 'image_id' => 1, 'name' => '⽂件袋'],
            ['id' => 7, 'image_id' => 1, 'name' => '挂画'],
            ['id' => 8, 'image_id' => 1, 'name' => '光盘'],
            ['id' => 9, 'image_id' => 1, 'name' => '台历'],
            ['id' => 10, 'image_id' => 1, 'name' => '纸杯'],
            ['id' => 11, 'image_id' => 1, 'name' => '宣传单'],
            ['id' => 12, 'image_id' => 1, 'name' => '封套'],
            ['id' => 13, 'image_id' => 1, 'name' => '手提袋'],
            ['id' => 14, 'image_id' => 1, 'name' => '代金券'],
            ['id' => 15, 'image_id' => 1, 'name' => '不干胶贴'],
            ['id' => 16, 'image_id' => 1, 'name' => '海报'],
            ['id' => 17, 'image_id' => 1, 'name' => '⼴告扇'],
            ['id' => 18, 'image_id' => 1, 'name' => 'x展架'],
            ['id' => 19, 'image_id' => 1, 'name' => '易拉宝'],
            ['id' => 20, 'image_id' => 1, 'name' => '⻔型展架'],
            ['id' => 21, 'image_id' => 1, 'name' => '写真画面'],
            ['id' => 22, 'image_id' => 1, 'name' => 'kt板写真'],
            ['id' => 23, 'image_id' => 1, 'name' => '条幅'],
            ['id' => 24, 'image_id' => 1, 'name' => '⽆纺布袋'],
            ['id' => 25, 'image_id' => 1, 'name' => '画册'],
            ['id' => 26, 'image_id' => 1, 'name' => '培训手册'],
            ['id' => 27, 'image_id' => 1, 'name' => '员⼯手册'],
            ['id' => 28, 'image_id' => 1, 'name' => '折页'],
        ]);
    }
}
