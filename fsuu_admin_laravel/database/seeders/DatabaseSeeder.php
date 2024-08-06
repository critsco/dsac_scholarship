<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            UserRoleSeeder::class,
            UserSeeder::class,
            FacultyCleanSeeder::class,
            ModuleAndRolePermissionSeeder::class,
            RefRateSeeder::class,
            RefStatusSeeder::class,
            RefCivilStatusSeeder::class,
            RefNationalitySeeder::class,
            RefLanguageSeeder::class,
            RefRegionSeeder::class,
            RefExamCategorySeeder::class,
            RefReligionSeeder::class,
            RefSchoolSeeder::class,
            // RefSchoolLevelSeeder::class,
            RefPositionSeeder::class,
            EmailTemplateSeeder::class,
            RefDepartmentSeeder::class,
        ]);
    }
}
