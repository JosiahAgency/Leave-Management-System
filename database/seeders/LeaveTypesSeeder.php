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
            [
                'name' => 'Annual Leave',
                'leaveRules' => 'Accrued annually. Must be pre-approved.',
                'numberOfDays' => 20,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Sick Leave',
                'leaveRules' => 'Requires medical certificate for absences longer than 2 days.',
                'numberOfDays' => 10,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Maternity Leave',
                'leaveRules' => 'Applicable to female employees. Government-regulated.',
                'numberOfDays' => 90,
                'weekendsInclusive' => 'Yes',
            ],
            [
                'name' => 'Paternity Leave',
                'leaveRules' => 'Applicable to male employees after childbirth.',
                'numberOfDays' => 10,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Compassionate Leave',
                'leaveRules' => 'Used in the event of a death or serious illness of a close relative.',
                'numberOfDays' => 5,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Study Leave',
                'leaveRules' => 'Proof of study or examination schedule required.',
                'numberOfDays' => 15,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Leave Without Pay',
                'leaveRules' => 'Unpaid leave must be approved by management.',
                'numberOfDays' => null,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Public Holiday',
                'leaveRules' => 'Mandated by the government calendar.',
                'numberOfDays' => 1,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Casual Leave',
                'leaveRules' => 'Short notice, non-medical emergencies.',
                'numberOfDays' => 7,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Medical Appointment Leave',
                'leaveRules' => 'Used for attending scheduled medical appointments.',
                'numberOfDays' => 3,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Bereavement Leave',
                'leaveRules' => 'Granted upon death of immediate family.',
                'numberOfDays' => 3,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Marriage Leave',
                'leaveRules' => 'Applicable to employee’s wedding.',
                'numberOfDays' => 5,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Religious Holiday',
                'leaveRules' => 'Subject to religious calendar. Proof required.',
                'numberOfDays' => 1,
                'weekendsInclusive' => 'No',
            ],
            [
                'name' => 'Emergency Leave',
                'leaveRules' => 'For unexpected, urgent personal matters.',
                'numberOfDays' => 2,
                'weekendsInclusive' => 'No',
            ]
        ];


        foreach ($leaveTypes as $leave) {
            LeaveType::create($leave);
        }
    }
}
