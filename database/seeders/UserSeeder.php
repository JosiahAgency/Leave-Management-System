<?php

namespace Database\Seeders;

use App\Models\Departments;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'departmentID' => 1,
            'supervisor' => 'System',
            'job_title' => 'Administrator',
            'password' => Hash::make('1234'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $admin->assignRole('admin');

        $departments = Departments::all();

        foreach ($departments as $department) {
            // Create 10 users per department
            $users = User::factory()->count(10)->create([
                'departmentID' => $department->id,
                'supervisor' => 'Jane Smith',
                'job_title' => 'Staff Member',
            ]);

            foreach ($users as $index => $user) {
                $user->assignRole('user');

                // Assign manager role to first user
                if ($index === 0) {
                    $user->assignRole('manager');
                    $user->update(['job_title' => 'Manager']);
                }
            }
        }
    }
}
