<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'type' => 'Employee',
                'role' => 'Super Admin',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Employee',
                'role' => 'Staff',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Employee',
                'role' => 'Faculty Admin',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Student',
                'role' => 'Student',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'Faculty',
                'role' => 'Faculty',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        UserRole::truncate();
        UserRole::insert($data);
    }
}
