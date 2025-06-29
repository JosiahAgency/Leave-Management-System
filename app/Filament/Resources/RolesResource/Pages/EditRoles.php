<?php

namespace App\Filament\Resources\RolesResource\Pages;

use App\Filament\Resources\RolesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoles extends EditRecord
{
        protected static string $resource = RolesResource::class;

        protected function getHeaderActions(): array
        {
                return [
                        Actions\DeleteAction::make(),
                ];
        }

        protected function getRedirectUrl(): ?string
        {
                $resources = static::getResource();
                return $resources::getUrl('index');
        }
}
