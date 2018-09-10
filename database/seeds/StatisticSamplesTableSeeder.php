<?php

use Illuminate\Database\Seeder;

class StatisticSamplesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [];
        for ($i = 1; $i <= 366; $i++) {
            array_push($arr, [
                'id' => $i
            ]);
        }

        \App\Models\StatisticSample::saveAll($arr);
    }
}
