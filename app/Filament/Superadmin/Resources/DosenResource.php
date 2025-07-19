<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\DosenResource\Pages;
use App\Filament\Superadmin\Resources\DosenResource\Pages\CreateDosen;
use App\Filament\Superadmin\Resources\DosenResource\Pages\EditDosen;
use App\Filament\Superadmin\Resources\DosenResource\RelationManagers;
use App\Models\Dosen;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class DosenResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Daftar Dosen';

    // protected static ?string $navigationGroup = 'User';
    protected static ?string $label = 'Daftar Dosen';

    public static ?string $pluralLabel = 'Daftar Dosen';

  public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Nama Lengkap'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Email')
                    ->unique(ignoreRecord: true),
                TextInput::make('nomor_induk')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Nomor Induk (NIDN)'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(static fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->required(static fn (Page $livewire): string => $livewire instanceof CreateDosen)
                    ->dehydrated(static fn (?string $state): bool => filled($state))
                    ->label(
                        static fn (Page $livewire): string => ($livewire instanceof EditDosen) ? 'Ganti Password' : 'Masukkan Password'
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
                fn (Builder $query) => $query->role('dosen')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nama Dosen'),
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
                    ->label('NIDN'),
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
            'index' => Pages\ListDosen::route('/'),
            'create' => Pages\CreateDosen::route('/create'),
            'edit' => Pages\EditDosen::route('/{record}/edit'),
        ];
    }
}
