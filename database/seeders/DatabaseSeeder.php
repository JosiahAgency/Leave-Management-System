<?php

namespace Database\Seeders;

use App\Filament\Resources\LeaveRequestApproveResource;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            LeaveTypesSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
        ]);
    }
}
