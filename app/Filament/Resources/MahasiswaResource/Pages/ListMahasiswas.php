<?php

namespace App\Filament\Resources\MahasiswaResource\Pages;

use App\Filament\Resources\MahasiswaResource;
use App\Imports\MahasiswaImport;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;

class ListMahasiswas extends ListRecords
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionGroup::make([
                Action::make('Ekspor Data')
                    ->icon('heroicon-m-document-arrow-up')
                    ->url(route('export-mahasiswa'))
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
                        Excel::import(new MahasiswaImport(), $data['file']);

                        // Memberi Notifikasi jika Sukses
                        Notification::make()
                            ->title('Berhasil Import Data Mahasiswa')
                            ->success()
                            ->seconds(3)
                            ->body('Import Data Mahasiswa berhasil.')
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
