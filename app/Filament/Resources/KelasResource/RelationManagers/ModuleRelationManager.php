<?php

namespace App\Filament\Resources\KelasResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModuleRelationManager extends RelationManager
{
    protected static string $relationship = 'module';

    // Label untuk tab/bagian di halaman Kelas
    protected static ?string $navigationLabel = 'Modul Pembelajaran';

    protected static ?string $pluralLabel = 'Modul Pembelajaran';

    protected static ?string $title = 'Modul Pembelajaran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tidak perlu memilih Kelas di sini, karena sudah otomatis
                // terhubung ke Kelas yang sedang dibuka (owner record)
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->label('Judul Modul'),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                // Forms\Components\TextInput::make('order')
                //     ->numeric()
                //     ->default(0)
                //     ->label('Urutan'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul Modul'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->sortable()
                    ->label('Deskripsi'),
                // Hitung jumlah materi dalam modul ini
                Tables\Columns\TextColumn::make('materials_count')
                    ->counts('materi')
                    ->label('Jumlah Materi'),
                // Hitung jumlah kuis dalam modul ini
                Tables\Columns\TextColumn::make('quizzes_count')
                    ->counts('kuis')
                    ->label('Jumlah Kuis'),
                TextColumn::make('class_id')
                    ->label('Kelas')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->kelas ? "{$record->kelas->name} {$record->kelas->offering} - {$record->kelas->batch_year}" : '';
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(), // Membuat modul baru untuk kelas ini
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Action untuk navigasi cepat ke detail/materi modul (Opsional)
                // Tables\Actions\Action::make('view_content')
                //     ->label('Lihat Konten')
                //     ->icon('heroicon-o-eye')
                //     ->url(fn (Module $record): string => ModuleResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
