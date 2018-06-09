<?php

use Illuminate\Database\Seeder;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Image::saveAll([
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
            ['src' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg'],
        ]);
    }
}
