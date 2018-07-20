<?php

use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\File::saveAll([
            ['name' => '印刷品网站计划表.rar', 'src' => 'files/XEHhWkQjQANN4P3dvICamFIvlGZ9gDZOZqp5dUAo.rar']
        ]);
    }
}
