<?php

namespace App\Filament\Resources\SoalResource\Pages;

use App\Filament\Resources\SoalResource;
use App\Imports\SoalImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Enums\ActionSize;

class ListSoals extends ListRecords
{
    protected static string $resource = SoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionGroup::make([
                Action::make('Ekspor Data')
                    ->icon('heroicon-m-document-arrow-up')
                    ->url(route('export-soal'))
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
                        Excel::import(new SoalImport(), $data['file']);

                        // Memberi Notifikasi jika Sukses
                        Notification::make()
                            ->title('Berhasil Import Data Soal')
                            ->success()
                            ->seconds(3)
                            ->body('Import Data Soal berhasil.')
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
