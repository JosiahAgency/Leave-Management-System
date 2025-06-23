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
