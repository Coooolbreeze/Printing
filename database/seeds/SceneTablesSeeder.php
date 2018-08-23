<?php

use Illuminate\Database\Seeder;

class SceneTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Scene::saveAll([
            ['id' => 1, 'name' => '地推套装'],
            ['id' => 2, 'name' => '会议办公'],
            ['id' => 3, 'name' => '门店宣传'],
            ['id' => 4, 'name' => '展会比备'],
        ]);

        \App\Models\SceneCategory::saveAll([
            ['id' => 1, 'scene_id' => 1, 'name' => '员工物料'],
            ['id' => 2, 'scene_id' => 1, 'name' => '活动物料'],
            ['id' => 3, 'scene_id' => 1, 'name' => '活动布置'],
            ['id' => 4, 'scene_id' => 2, 'name' => '员工物料'],
            ['id' => 5, 'scene_id' => 2, 'name' => '企业资料'],
            ['id' => 6, 'scene_id' => 2, 'name' => '办公装饰'],
            ['id' => 7, 'scene_id' => 3, 'name' => '门店展示'],
            ['id' => 8, 'scene_id' => 3, 'name' => '拓客宣传'],
            ['id' => 9, 'scene_id' => 3, 'name' => '日常采购'],
            ['id' => 10, 'scene_id' => 4, 'name' => '人员物资'],
            ['id' => 11, 'scene_id' => 4, 'name' => '展会布置'],
            ['id' => 12, 'scene_id' => 4, 'name' => '宣传资料'],
        ]);
    }
}
