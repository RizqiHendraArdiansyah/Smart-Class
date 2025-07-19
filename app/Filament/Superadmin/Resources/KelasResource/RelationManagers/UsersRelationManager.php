<?php

namespace App\Filament\Superadmin\Resources\KelasResource\Pages;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $navigationLabel = 'Daftar Mahasiswa';

    protected static ?string $pluralLabel = 'Daftar Mahasiswa';

    protected static ?string $title = 'Daftar Mahasiswa';
    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Select::make('user_id')
    //                 ->searchable()
    //                 ->relationship('user', 'id')
    //                 ->required(),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Mahasiswa'),
                Tables\Columns\TextColumn::make('nomor_induk')
                    ->label('NIM Mahasiswa'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Ganti CreateAction dengan AttachAction
                Tables\Actions\AttachAction::make()
                    ->label('Tambahkan Mahasiswa')
                    ->modalHeading('Pilih Mahasiswa untuk Ditambahkan')
                    -> // Definisikan form di dalam AttachAction untuk memilih user
                    form(fn(Tables\Actions\AttachAction $action): array => [
                        Forms\Components\Select::make('recordId') // 'recordId' adalah key konvensi Filament
                            ->label('Mahasiswa')
                            // ->// Query HANYA user dengan role 'Mahasiswa'
                            // options(User::query()->whereHas('roles', fn(Builder $query) => $query->where('name', 'mahasiswa'))->pluck('name', 'id'))
                            ->multiple() // Izinkan memilih banyak user sekaligus
                            ->searchable()
                            ->required()
                            ->preload() // Preload options jika jumlah user tidak terlalu besar
                            // Filament otomatis mengecualikan user yang sudah ter-attach
                            ->options(fn() => User::whereDoesntHave('kelas', fn($query) => $query->where('class_id', $this->ownerRecord->id))
                                ->whereHas('roles', fn(Builder $query) => $query->where('name', 'Mahasiswa'))
                                ->pluck('name', 'id')),
                    ])
                    ->preloadRecordSelect(), // Preload user yang sudah ter-attach jika diedit (meski edit tidak dipakai di sini)
            ])
            ->actions([
                // Ganti DeleteAction dengan DetachAction
                Tables\Actions\DetachAction::make()
                    ->label('Keluarkan dari Kelas'),
                // EditAction biasanya tidak relevan di sini, karena akan mengedit data User, bukan hubungan kelas
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Ganti DeleteBulkAction dengan DetachBulkAction
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Keluarkan Terpilih dari Kelas'),
                ]),
            ]);
    }
}
