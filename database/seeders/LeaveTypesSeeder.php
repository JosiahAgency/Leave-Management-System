<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            ['name' => 'Annual Leave'],
            ['name' => 'Sick Leave'],
            ['name' => 'Maternity Leave'],
            ['name' => 'Paternity Leave'],
            ['name' => 'Compassionate Leave'],
            ['name' => 'Study Leave'],
            ['name' => 'Leave Without Pay'],
            ['name' => 'Public Holiday'],
            ['name' => 'Casual Leave'],
            ['name' => 'Medical Appointment Leave'],
            ['name' => 'Bereavement Leave'],
            ['name' => 'Marriage Leave'],
            ['name' => 'Religious Holiday'],
            ['name' => 'Emergency Leave']
        ];

        foreach ($leaveTypes as $leave) {
            LeaveType::create($leave);
        }
    }
}
