<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModulResource\Pages;
use App\Models\Kelas\KelasModel;
use App\Models\Modul\ModulModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ModulResource extends Resource
{
    protected static ?string $model = ModulModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    // protected static ?string $navigationGroup = 'Materi';

    protected static ?string $navigationLabel = 'Modul';

    
    public static ?string $label = 'Modul';

    protected static ?string $pluralLabel = 'Modul Pembelajaran';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make(name: 'class_id')
                    ->options(function (callable $get) {
                        $kelas = KelasModel::all();

                        // Map data to "{kode} - {nama barang}" format
                        return $kelas->mapWithKeys(function ($item) {
                            $kodeNama = "{$item->name} {$item->offering} - {$item->batch_year}";

                            return [$item->id => $kodeNama];
                        });
                    })
                    // ->relationship('kelas', 'name') // Relasi ke ClassModel, tampilkan 'name'
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->preload()
                    ->label('Kelas'),
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->label('Judul'),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Deskripsi'),
                TextInput::make('waktu')
                    ->columnSpanFull()
                    ->required()
                    ->numeric()
                    ->suffix('Menit')
                    ->label('Durasi (menit)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('kelas'))
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul Modul'),
                TextColumn::make('description')
                    ->label('Deskripsi'),
                TextColumn::make('class_id')
                    ->label('Kelas')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->kelas ? "{$record->kelas->name} {$record->kelas->offering} - {$record->kelas->batch_year}" : '';
                    }),
                // ->searchable(['name', 'offering', 'batch_year']),
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
            'index' => Pages\ListModuls::route('/'),
            'create' => Pages\CreateModul::route('/create'),
            'edit' => Pages\EditModul::route('/{record}/edit'),
        ];
    }
}
