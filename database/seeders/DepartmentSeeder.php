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
            ['name' => 'Administration'],
            ['name' => 'Human Resources'],
            ['name' => 'Finance'],
            ['name' => 'Operations'],
            ['name' => 'Sales'],
            ['name' => 'Marketing'],
            ['name' => 'Customer Service'],
            ['name' => 'Procurement'],
            ['name' => 'Legal']
        ];

        foreach ($departments as $department) {
            Departments::create($department);
        }
    }
}
