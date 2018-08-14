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
            LargeCategoriesTableSeeder::class,
            CategoriesTableSeeder::class,
            TypesTableSeeder::class,
            SecondaryTypesTableSeeder::class,
            CommonAttributesTableSeeder::class,
            CommonValuesTableSeeder::class,
            ImagesTableSeeder::class,
            FilesTableSeeder::class,
            NewsCategoriesTableSeeder::class,
            MemberLevelsTableSeeder::class,
            CacheFlushSeeder::class,
        ]);
    }
}
