<?php

namespace App\Filament\Resources\LeaveRequestResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\LeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveRequests extends ListRecords
{
        protected static string $resource = LeaveRequestResource::class;

        protected static ?string $title = 'My Leave Requests';

        protected function getHeaderActions(): array
        {
                $user = auth()->user();

                $actions = [
                        Actions\CreateAction::make()
                                ->color('warning')
                                ->icon('heroicon-o-arrow-down-on-square-stack')
                                ->label('Apply for Leave'),
                ];

                if ($user->hasRole('admin') || $user->hasRole('manager') || $user->hasRole('humanResources')) {
                        $actions [] =
                                Actions\Action::make('approvals')
                                        ->label('Requests Awaiting Approval')
                                        ->color('danger')
                                        ->icon('heroicon-o-bell-alert')
                                        ->url('leave-requests/approvals');
                }

                return $actions;
        }

}
