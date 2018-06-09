<?php

use Illuminate\Database\Seeder;

class PermissionTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        \Spatie\Permission\Models\Permission::create(['name' => 'search']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'recommend']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'news']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'help']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'activity']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'scene']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'goods']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'order']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'integral']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'user']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'tool']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'coupon']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'finance']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'distribution']);
//        \Spatie\Permission\Models\Permission::create(['name' => 'data']);

        \Spatie\Permission\Models\Permission::create(['name' => '搜索管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '首页推荐管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '新闻管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '帮助中心管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '活动管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '场景用途管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '商品管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '订单管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '积分管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '用户管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '客服工具管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '优惠券管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '财务管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '配送管理']);
        \Spatie\Permission\Models\Permission::create(['name' => '数据中心']);

        $super = \Spatie\Permission\Models\Role::create(['name' => 'super']);
        $super->givePermissionTo([
            '搜索管理', '首页推荐管理', '新闻管理', '帮助中心管理', '活动管理',
            '场景用途管理', '商品管理', '订单管理', '积分管理', '用户管理',
            '客服工具管理', '优惠券管理', '财务管理', '配送管理', '数据中心'
        ]);

        $super = \Spatie\Permission\Models\Role::create(['name' => '1']);
        $super->givePermissionTo(['搜索管理', '首页推荐管理', '新闻管理', '帮助中心管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '2']);
        $super->givePermissionTo(['活动管理', '场景用途管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '3']);
        $super->givePermissionTo(['商品管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '4']);
        $super->givePermissionTo(['订单管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '5']);
        $super->givePermissionTo(['积分管理', '用户管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '6']);
        $super->givePermissionTo(['客服工具管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '7']);
        $super->givePermissionTo(['优惠券管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '8']);
        $super->givePermissionTo(['财务管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '9']);
        $super->givePermissionTo(['配送管理']);

        $super = \Spatie\Permission\Models\Role::create(['name' => '10']);
        $super->givePermissionTo(['数据中心']);
    }
}
