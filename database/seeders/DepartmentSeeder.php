<?php

namespace Database\Seeders;

use App\Models\Departments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'IT'],
            ['name' => 'Human Resources'],
            ['name' => 'Finance'],
            ['name' => 'Sales'],
            ['name' => 'Customer Service'],
        ];

        foreach ($departments as $department) {
            Departments::create($department);
        }
    }
}
