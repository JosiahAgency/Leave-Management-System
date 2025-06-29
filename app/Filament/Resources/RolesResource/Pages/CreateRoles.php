<?php

namespace App\Filament\Resources\RolesResource\Pages;

use App\Filament\Resources\RolesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRoles extends CreateRecord
{
        protected static string $resource = RolesResource::class;

        protected function getRedirectUrl(): string
        {
                $resources = static::getResource();
                return $resources::getUrl('index');
        }
}
