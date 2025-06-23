<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaveRequests extends Seeder
{
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
                $users = DB::table('users')->pluck('id');
                $departments = DB::table('departments')->pluck('id');
                $leaveTypes = DB::table('leave_types')->pluck('id');

                if ($users->isEmpty() || $departments->isEmpty() || $leaveTypes->isEmpty()) {
                        $this->command->warn("Ensure users, departments, and leave types are seeded before this.");
                        return;
                }

                foreach (range(1, 20) as $i) {
                        $start = Carbon::now()->addDays(rand(1, 30));
                        $end = (clone $start)->addDays(rand(1, 5));

                        DB::table('leave_requests')->insert([
                                'userID' => $users->random(),
                                'leaveTypeID' => $leaveTypes->random(),
                                'departmentID' => $departments->random(),
                                'startDate' => $start->toDateString(),
                                'endDate' => $end->toDateString(),
                                'reason' => fake()->sentence(),
                                'supportingDocument' => rand(0, 1) ? 'support_docs/' . Str::random(10) . '.pdf' : null,
                                'status' => fake()->randomElement(['Pending', 'Granted', 'Denied']),
                                'hod_approval' => fake()->randomElement(['pending', 'approved', 'rejected']),
                                'hr_approval' => fake()->randomElement(['pending', 'approved', 'rejected']),
                                'hod_comment' => rand(0, 1) ? fake()->sentence() : null,
                                'hr_comment' => rand(0, 1) ? fake()->sentence() : null,
                                'created_at' => now(),
                                'updated_at' => now(),
                        ]);
                }
        }
}
