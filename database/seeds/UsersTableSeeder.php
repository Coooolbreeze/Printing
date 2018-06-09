<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::create([
            'id' => 1,
            'nickname' => '超级管理员',
            'avatar' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg',
            'sex' => 0,
            'account' => 'administrator',
            'is_bind_account' => 1,
            'is_admin' => 1
        ]);

        $user->assignRole('super');
    }
}
