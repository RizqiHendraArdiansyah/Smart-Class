<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KuisResource\Pages;
use App\Models\Kuis\KuisModel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KuisResource extends Resource
{
    protected static ?string $model = KuisModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $navigationLabel = 'Kuis';

    // protected static ?string $navigationGroup = 'Materi';

    public static ?string $label = 'Kuis';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->columnSpanFull()
                    ->label('Judul Kuis'),
                Select::make('module_id')
                    ->preload()
                    ->searchable()
                    ->columnSpanFull()
                    ->label('Modul')
                    ->relationship('modul', 'title')
                    ->required(),
                TextInput::make('description')
                    ->columnSpanFull()
                    ->label('Deskripsi'),
                DatePicker::make('deadline')
                    ->native(false)
                    ->prefixIcon('heroicon-m-calendar-days')
                    ->label('Deadline'),

                Select::make('is_aktif')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tutup' => 'Ditutup',
                        'datang' => 'Akan Datang',
                    ]),
                TextInput::make('time_limit_minutes')
                    ->columnSpanFull()
                    ->required()
                    ->numeric()
                    ->suffix('Menit')
                    ->label('Durasi (menit)'),

                Section::make('Pengaturan Akses')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_passcode_enabled')
                                    ->label('Aktifkan Passcode')
                                    ->default(false)
                                    ->reactive()
                                    ->helperText('Mahasiswa harus memasukkan passcode untuk mengakses kuis'),
                                TextInput::make('passcode')
                                    ->label('Passcode Kuis')
                                    ->placeholder('Masukkan passcode')
                                    ->password()
                                    ->visible(fn ($get) => $get('is_passcode_enabled'))
                                    ->required(fn ($get) => $get('is_passcode_enabled'))
                                    ->minLength(4)
                                    ->maxLength(20)
                                    ->helperText('Minimal 4 karakter')
                                    ->revealable(),
                            ]),
                    ])
                    ->collapsible(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul'),
                TextColumn::make('modul.kelas.name')
                    ->label('Kelas'),
                TextColumn::make('modul.kelas.user.name')
                    ->label('Dosen'),
                TextColumn::make('deadline')
                    ->date('d F Y')
                    ->label('Deadline'),
                TextColumn::make('is_aktif')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'aktif' => 'Aktif',
                        'tutup' => 'Ditutup',
                        'datang' => 'Akan Datang',
                        default => $state,
                    })
                    ->color(fn ($state): string => match ($state) {
                        'aktif' => 'success',
                        'tutup' => 'danger',
                        'datang' => 'primary',
                        default => 'warning'
                    })
                    ->badge()
                    ->label('Status'),
                TextColumn::make('time_limit_minutes')
                    ->label('Durasi'),
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
            'index' => Pages\ListKuis::route('/'),
            'create' => Pages\CreateKuis::route('/create'),
            'edit' => Pages\EditKuis::route('/{record}/edit'),
        ];
    }
}
