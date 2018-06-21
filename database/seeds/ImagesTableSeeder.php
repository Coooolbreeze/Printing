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
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
            ['src' => 'images/7gAHc2nX9sVng6jYCN3rdvqv8xsVmfW3uZVPhVbe.jpeg'],
        ]);
    }
}
