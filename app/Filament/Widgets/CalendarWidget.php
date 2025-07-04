<?php

namespace App\Filament\Widgets;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = LeaveRequest::class;

    protected function headerActions(): array
    {
        return [
        ];
    }

    protected function modalActions(): array
    {
        return [
        ];
    }

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

    public function getFormSchema(): array
    {
        return [
            Fieldset::make('')
                ->schema([
                    Select::make('userID')
                        ->label('Staff Name')
                        ->disabled()
                        ->relationship('user', 'name'),
                    Select::make('departmentID')
                        ->label('Department')
                        ->disabled()
                        ->relationship('department', 'name')

                ]),
            Fieldset::make('')
                ->schema([
                    Select::make('leaveTypeID')
                        ->label('Type of Leave')
                        ->disabled()
                        ->relationship('leaveType', 'name'),
                    TextInput::make('status')
                        ->readOnly(),
                    TextInput::make('startDate')
                        ->readOnly(),
                    TextInput::make('endDate')
                        ->readOnly(),
                ]),
            Fieldset::make('')
                ->schema([
                    TextInput::make('reason')
                        ->readOnly(),
                ])->columns(1)
        ];
    }
}

