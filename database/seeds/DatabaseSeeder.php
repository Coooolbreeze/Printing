<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionTablesSeeder::class,
            UsersTableSeeder::class,
            UserAuthsTableSeeder::class,
            CategoriesTableSeeder::class,
            CommonAttributesTableSeeder::class,
            CommonValuesTableSeeder::class,
            ImagesTableSeeder::class,
            FilesTableSeeder::class,
            MemberLevelsTableSeeder::class,
            CacheFlushSeeder::class,
        ]);
    }
}
