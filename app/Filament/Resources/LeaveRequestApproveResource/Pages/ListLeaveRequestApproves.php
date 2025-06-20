<?php

namespace App\Filament\Resources\LeaveRequestApproveResource\Pages;

use App\Filament\Resources\LeaveRequestApproveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveRequestApproves extends ListRecords
{
    protected static string $resource = LeaveRequestApproveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
