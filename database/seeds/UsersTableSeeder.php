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
            'avatar' => config('setting.default_avatar_url'),
            'sex' => 0,
            'account' => 'administrator',
            'accumulate_points' => 100000,
            'history_accumulate_points' => 100000,
            'is_bind_account' => 1,
            'is_admin' => 1
        ]);

        $user->assignRole('super');
    }
}
