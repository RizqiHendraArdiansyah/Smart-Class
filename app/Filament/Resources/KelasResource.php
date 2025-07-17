<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\Pages\ModuleRelationManager;
use App\Filament\Resources\KelasResource\Pages\UsersRelationManager;
use App\Models\Kelas\KelasModel;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelasResource extends Resource
{
    protected static ?string $model = KelasModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Daftar Kelas';

    // protected static ?string $navigationGroup = 'Settings';
    // protected static ?string $navigationGroup = 'User';
    public static ?string $label = 'Daftar Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->label('Kelas'),
                Select::make('user_id')
                    ->searchable()
                    ->preload()
                    ->options(fn () => User::role('dosen')->pluck('name', 'id'))
                    ->required()
                    ->label('Dosen')
                    ->columnSpanFull(),
                Select::make('semester')
                    ->required()
                    ->columnSpanFull()
                    ->label('Semester')
                    ->options([
                        1 => 'Ganjil',
                        2 => 'Genap',
                    ]),
                Select::make('batch_year')
                    ->required()
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->label('Tahun Ajaran')
                    ->options(function () {
                        $currentYear = (int) date('Y');
                        $years = range(2000, $currentYear);

                        return array_combine($years, $years);
                    })
                    ->default(date('Y')),

                TextInput::make('offering')
                    ->columnSpanFull()
                    // ->required()
                    ->label('Offering'),
                Textarea::make('description')
                    ->columnSpanFull()
                    // ->required()
                    ->label('Deskripsi'),
                Select::make('is_aktif')
                    ->required()
                    ->columnSpanFull()
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kelas'),
                TextColumn::make('user.name')
                    ->label('Dosen'),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Ganjil',
                        2 => 'Genap',
                        default => ''
                    }),
                TextColumn::make('batch_year')
                    ->label('Angkatan'),

                TextColumn::make('is_aktif')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        0 => 'danger',
                        1 => 'success',
                        default => 'warning'
                    })
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        0 => 'Tidak Aktif',
                        1 => 'Aktif',
                        default => ''
                    })
                    ->label('Status'),

                TextColumn::make('users_count')
                    // ->formatStateUsing(function ($record) {
                    //     return $record->user()
                    //         ->whereHas('roles', fn($q) => $q->where('name', 'mahasiswa'))
                    //         ->count();
                    // })
                    ->counts(
                        'users',
                        fn ($query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'mahasiswa'))
                    )
                    ->label('Jumlah Mahasiswa')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
            UsersRelationManager::class,
            ModuleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
