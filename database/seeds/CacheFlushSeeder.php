<?php

use Illuminate\Database\Seeder;

class CacheFlushSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::flush();
    }
}
