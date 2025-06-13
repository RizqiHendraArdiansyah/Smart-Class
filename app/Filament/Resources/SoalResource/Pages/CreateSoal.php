<?php

namespace App\Filament\Resources\SoalResource\Pages;

use App\Filament\Resources\SoalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSoal extends CreateRecord
{
    protected static string $resource = SoalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Unset helper fields yang tidak perlu disimpan
        unset($data['class_id']);

        return $data;
    }
}
