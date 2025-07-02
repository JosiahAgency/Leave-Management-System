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
