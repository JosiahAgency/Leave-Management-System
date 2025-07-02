<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'userID',
        'departmentID',
        'leaveTypeID',
        'startDate',
        'endDate',
        'reason',
        'supportingDocument',
        'status'
    ];

    protected static function booted()
    {
        static::created(function ($leaveRequest) {
            $start = \Carbon\Carbon::parse($leaveRequest->startDate);
            $end = \Carbon\Carbon::parse($leaveRequest->endDate);
            $daysRequested = $start->diffInDaysFiltered(fn($date) => $date->isWeekday(), $end->copy()->addDay());

            $leaveRequest->user->decrement('leaveDays', $daysRequested);
        });

        static::deleting(function ($leaveRequest) {
            $user = $leaveRequest->user;

            // Calculate the number of leave days
            $start = \Carbon\Carbon::parse($leaveRequest->startDate);
            $end = \Carbon\Carbon::parse($leaveRequest->endDate);

            $count = 0;
            $date = $start->copy();

            // If the leave included weekends or not
            $includeWeekends = $leaveRequest->weekendsInclusive === 'Yes';

            while ($date->lte($end)) {
                if ($includeWeekends || !$date->isWeekend()) {
                    $count++;
                }
                $date->addDay();
            }

            // Add the days back to user's leave_days
            $user->increment('leaveDays', $count);
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leaveTypeID');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'departmentID');
    }

}
