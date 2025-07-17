<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Filament\Resources\MahasiswaResource\Pages\CreateMahasiswa;
use App\Filament\Resources\MahasiswaResource\Pages\EditMahasiswa;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class MahasiswaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Daftar Mahasiswa';

    // protected static ?string $navigationGroup = 'Settings';
    // protected static ?string $navigationGroup = 'User';
    public static ?string $label = 'Daftar Mahasiswa';

    protected static ?string $pluralLabel = 'Daftar Mahasiswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Nama Kamu'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Email')
                    ->unique(ignoreRecord: true),
                TextInput::make('nomor_induk')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nomor induk'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(static fn(?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->required(static fn(Page $livewire): string => $livewire instanceof CreateMahasiswa)
                    ->dehydrated(static fn(?string $state): bool => filled($state))
                    ->label(
                        static fn(Page $livewire): string => ($livewire instanceof EditMahasiswa) ? 'Ganti Password' : 'Masukkan Password'
                    ),
                // Forms\Components\DateTimePicker::make('email_verified_at'),
                // Forms\Components\TextInput::make('theme')
                //     ->maxLength(255)
                //     ->default('default'),
                // Forms\Components\TextInput::make('theme_color')
                //     ->maxLength(255),
                // Select::make('roles')
                //     ->preload()
                //     ->multiple()
                //     ->relationship('roles', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query->role('mahasiswa')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('email_verified_at')
                //     ->dateTime('d F Y, H:i')
                //     ->label('Email diverifikasi sejak')
                //     ->sortable(),
                Tables\Columns\TextColumn::make('nomor_induk')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Berhasil Disalin')
                    ->sortable()
                    ->label('NIM'),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Berhasil Disalin')
                    ->sortable()
                    ->label('Role'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
