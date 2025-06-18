<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'userID',
        'leaveTypeID',
        'supervisor',
        'startDate',
        'endDate',
        'reason',
        'supportingDocument',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leaveTypeID');
    }

}
