<?php

namespace App\Filament\Resources\LeaveRequestResource\Pages;

use App\Filament\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use Filament\Actions;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class Approvals extends ListRecords
{
        protected static string $resource = LeaveRequestResource::class;

        protected static ?string $title = 'Requests Awaiting for Approval';

        public function table(Table $table): Table
        {
                return $table
                        ->query($this->getTableQuery())
                        ->columns([
                                TextColumn::make('user.name')
                                        ->searchable()
                                        ->label('Employee'),
                                TextColumn::make('department.name')
                                        ->searchable()
                                        ->sortable(),
                                TextColumn::make('startDate')->label('Start')->date(),
                                TextColumn::make('endDate')->label('End')->date(),
                                TextColumn::make('leaveType.name')->searchable()->sortable(),
                                TextColumn::make('hod_approval')
                                        ->label('HOD')
                                        ->badge()
                                        ->color(fn(string $state): string => match ($state) {
                                                'pending' => 'warning',
                                                'rejected' => 'danger',
                                                'approved' => 'success'
                                        })
                                        ->searchable()
                                        ->sortable(),
                                TextColumn::make('hr_approval')
                                        ->label('HR')
                                        ->badge()
                                        ->color(fn(string $state): string => match ($state) {
                                                'pending' => 'warning',
                                                'rejected' => 'danger',
                                                'approved' => 'success'
                                        })
                                        ->searchable()
                                        ->sortable(),
                        ])->defaultSort('updated_at', 'desc')
                        ->actions($this->getTableActions())
                        ->emptyStateIcon('heroicon-o-bookmark')
                        ->emptyStateDescription('Once staff apply for leave and they are cleared by there departments then they shall visible for clearing');
        }

        public function getTableQuery(): Builder
        {
                $user = auth()->user();
                if ($user->hasRole('admin')) {
                        return LeaveRequest::query();
                } elseif ($user->hasRole('manager')) {
                        return LeaveRequest::query()
                                ->where('departmentID', $user->departmentID)
                                ->where('hod_approval', 'pending')
                                ->where('userID', '!=', $user->id);
                } elseif ($user->hasRole('humanResources')) {
                        return LeaveRequest::query()
                                ->where('hod_approval', 'approved')
                                ->where('hr_approval', 'pending')
                                ->where('userID', '!=', $user->id);
                } elseif ($user->hasRole('director')) {
                        return LeaveRequest::query()
                                ->where(function ($query) {
                                        $query->where('hr_approval', 'approved')
                                                ->orWhere('hod_approval', 'approved');
                                })->where('director_approval', 'pending');
                } else {
                        return LeaveRequest::query()->whereNull('id');
                }
        }

        public function getTableActions(): array
        {
                $user = auth()->user();
                $actions = [];

                if ($user->hasAnyRole(['admin', 'humanResources'])) {
                        $actions[] = Action::make('approve')
                                ->label('HR Approve')
                                ->color('success')
                                ->button()
                                ->hidden(function ($record) {
                                        return $record->hr_approval === 'approved' ? true : false;
                                })
                                ->requiresConfirmation()
                                ->action(function ($record) {
                                        $record->hr_approval = 'approved';
                                        $record->status = 'Granted';
                                        $record->save();
                                });

                        $actions[] = Action::make('reject')
                                ->label('HR Reject')
                                ->color('danger')
                                ->button()
                                ->hidden(function ($record) {
                                        return $record->hr_approval === 'rejected' ? true : false;
                                })
                                ->requiresConfirmation()
                                ->action(function ($record) {
                                        $record->hr_approval = 'rejected';
                                        $record->status = 'Denied';
                                        $record->save();
                                });
                }

                if ($user->hasAnyRole(['admin', 'manager'])) {
                        $actions[] = Action::make('approve')
                                ->label('HOD Approve')
                                ->button()
                                ->hidden(function ($record) {
                                        return $record->hod_approval === 'approved' ? true : false;
                                })
                                ->color('success')
                                ->requiresConfirmation()
                                ->action(function ($record) {
                                        $record->hod_approval = 'approved';
                                        $record->save();
                                });

                        $actions[] = Action::make('reject')
                                ->label('HOD Reject')
                                ->button()
                                ->color('danger')
                                ->hidden(function ($record) {
                                        return $record->hod_approval === 'rejected' ? true : false;
                                })
                                ->requiresConfirmation()
                                ->action(function ($record) {
                                        $record->hod_approval = 'rejected';
                                        $record->save();
                                });
                }

                return $actions;

        }

        protected function getHeaderActions(): array
        {
                return [
                        Actions\Action::make('list')
                                ->color('warning')
                                ->icon('heroicon-o-arrow-down-on-square-stack')
                                ->label('View My Leave Requests')
                                ->url('/admin/leave-requests'),
                ];
        }

}
