<?php

namespace App\Filament\Resources\DosenResource\Pages;

use App\Filament\Resources\DosenResource;
use App\Imports\DosenImport;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;

class ListDosens extends ListRecords
{
    protected static string $resource = DosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionGroup::make([
                Action::make('Ekspor Data')
                    ->icon('heroicon-m-document-arrow-up')
                    ->url(route('export-dosen'))
                    ->openUrlInNewTab(),
                Action::make('Import Data')
                    ->outlined()
                    ->icon('heroicon-m-document-arrow-down')
                    ->form([
                        FileUpload::make('file')
                            ->label('File Excel')
                            ->preserveFilenames()
                            ->storeFiles(false)
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        Excel::import(new DosenImport(), $data['file']);

                        // Memberi Notifikasi jika Sukses
                        Notification::make()
                            ->title('Berhasil Import Data Dosen')
                            ->success()
                            ->seconds(3)
                            ->body('Import Data Dosen berhasil.')
                            ->send();
                    }),
            ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('warning')
                ->button(),
        ];
    }
}
