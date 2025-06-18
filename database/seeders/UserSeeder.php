<?php

namespace Database\Seeders;

use App\Models\Departments;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = [
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'departmentID' => 1,
                'supervisor' => 'System', 
                'job_title' => 'Administrator',
                'password' => Hash::make('1234'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
        ];

        foreach ($admin as $admins) {
            User::create($admins);
        }

        $departments = Departments::all(); 

        $jobTitles = [
            'Software Engineer',
            'System Administrator',
            'Product Manager',
            'Finance Officer',
            'HR Specialist',
            'Sales Executive',
            'Marketing Manager',
            'Customer Support Agent',
            'IT Support Analyst',
            'Operations Coordinator',
        ];

        $departmentManagers = [];

        foreach ($departments as $department) {
            $manager = User::create([
                'name' => fake()->name,
                'email' => fake()->unique()->safeEmail,
                'departmentID' => $department->id,
                'supervisor' => 'CEO', // or 'System'
                'job_title' => 'Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            $departmentManagers[$department->id] = $manager->name;
        }

        for ($i = 1; $i <= 50; $i++) {
            $departmentID = fake()->randomElement($departments->pluck('id')->toArray());

            User::create([
                'name' => fake()->name,
                'email' => fake()->unique()->safeEmail,
                'departmentID' => $departmentID,
                'supervisor' => $departmentManagers[$departmentID],
                'job_title' => fake()->randomElement($jobTitles),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
