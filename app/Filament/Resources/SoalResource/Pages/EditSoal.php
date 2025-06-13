<?php

namespace App\Filament\Resources\SoalResource\Pages;

use App\Filament\Resources\SoalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSoal extends EditRecord
{
    protected static string $resource = SoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Unset helper fields yang tidak perlu disimpan
        unset(
            $data['class_id'],
            $data['module_id']
        );

        return $data;
    }
}
