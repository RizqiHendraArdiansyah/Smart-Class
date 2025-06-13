<?php

namespace App\Filament\Resources\MateriResource\Pages;

use App\Filament\Resources\MateriResource;
use App\Models\Materi\MateriModel;
use Filament\Resources\Pages\CreateRecord;

class CreateMateri extends CreateRecord
{
    protected static string $resource = MateriResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $game = MateriModel::all()->count();
        $data['order'] = $game + 1;

        return $data;
    }
}
