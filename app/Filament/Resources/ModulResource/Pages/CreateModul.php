<?php

namespace App\Filament\Resources\ModulResource\Pages;

use App\Filament\Resources\ModulResource;
use App\Models\Modul\ModulModel;
use Filament\Resources\Pages\CreateRecord;

class CreateModul extends CreateRecord
{
    protected static string $resource = ModulResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $game = ModulModel::all()->count();
        $data['order'] = $game + 1;

        return $data;
    }
}
