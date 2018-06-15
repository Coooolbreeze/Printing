<?php

use Illuminate\Database\Seeder;

class FreeExpressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\FreeExpress::create(['price' => 0]);
    }
}
