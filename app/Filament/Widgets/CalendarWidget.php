<?php

namespace App\Filament\Widgets;

use App\Models\LeaveRequest;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = LeaveRequest::class;

    public function fetchEvents(array $fetchInfo): array
    {
        $start = Carbon::parse($fetchInfo['start']);
        $end = Carbon::parse($fetchInfo['end']);
        $user = auth()->user();

        $query = LeaveRequest::with(['user:id,name', 'leaveType:id,name'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('startDate', [$start, $end])
                    ->orWhereBetween('endDate', [$start, $end]);
            });

        switch (true) {
            case $user->hasRole('admin'):
                break;
            case $user->hasAnyRole(['manager', 'humanResources']):
                $query->where('departmentID', $user->departmentID);
                break;
            default:
                $query->where('userID', $user->id);
        }


        return $query->get()->map(fn($leave) => [
            'id' => $leave->id,
            'title' => "{$leave->user->name}, {$leave->leaveType->name}, {$leave->department->name}",
            'start' => $leave->startDate,
            'end' => Carbon::parse($leave->endDate)->addDay(),
            'color' => match ($leave->status) {
                'Granted' => '#16a34a',
                'Pending' => '#facc15',
                'Denied' => 'red',
                default => '#dc2626',
            },
            'allDay' => true,
        ])->toArray();
    }
}

