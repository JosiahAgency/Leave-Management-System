<?php

namespace App\Filament\Resources\LeaveRequestApproveResource\Pages;

use App\Filament\Resources\LeaveRequestApproveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaveRequestApprove extends EditRecord
{
    protected static string $resource = LeaveRequestApproveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        $resource = static::getResource();
        return $resource->getUrl('index');
    }
}
