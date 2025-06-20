<?php

namespace App\Filament\Resources\LeaveRequestApproveResource\Pages;

use App\Filament\Resources\LeaveRequestApproveResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequestApprove extends CreateRecord
{
    protected static string $resource = LeaveRequestApproveResource::class;

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();
        return $resource->getUrl('index');
    }
}
