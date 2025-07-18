<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MateriResource\Pages;
use App\Models\Materi\MateriModel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MateriResource extends Resource
{
    protected static ?string $model = MateriModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';

    // protected static ?string $navigationGroup = 'Materi';

    protected static ?string $navigationLabel = 'Materi';

    public static ?string $label = 'Materi';

    protected static ?string $pluralLabel = 'Materi Pembelajaran';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('module_id')
                    ->searchable()
                    ->preload()
                    ->relationship('module', 'title')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->columnSpanFull()
                    ->required(),
                Select::make('type')
                    ->columnSpanFull()
                    ->options([
                        'text' => 'Text',
                        // 'video' => 'Video',
                        // 'pdf' => 'PDF',
                        // 'link' => 'Link',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn($state, $set) => $set('content', null)),

                RichEditor::make('content')
                    ->columnSpanFull()
                    // ->visible(fn($get) => $get('type') === 'text')
                    ->required(fn($get) => $get('type') === 'text')
                    ->fileAttachmentsDirectory('materi/images')
                    ->fileAttachmentsVisibility('public'),
                // Grid::make(1)
                //     ->schema([
                // RichEditor::make('content')
                //     ->visible(fn($get) => $get('type') === 'text')
                //     ->required(fn($get) => $get('type') === 'text')
                //     ->fileAttachmentsDirectory('materi/images')
                //     ->fileAttachmentsVisibility('public'),

                // FileUpload::make('content')
                //     ->visible(fn($get) => $get('type') === 'pdf')
                //     ->required(fn($get) => $get('type') === 'pdf')
                //     ->acceptedFileTypes(['application/pdf'])
                //     ->directory('materi/pdf'),

                //         TextInput::make('content')
                //             ->visible(fn ($get) => in_array($get('type'), ['video', 'link']))
                //             ->required(fn ($get) => in_array($get('type'), ['video', 'link']))
                //             ->label(fn ($get) => $get('type') === 'video' ? 'URL Video' : 'URL Link')
                //             ->placeholder(fn ($get) => $get('type') === 'video' ? 'Masukkan URL video' : 'Masukkan URL link'),
                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orderBy('order'))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul'),
                TextColumn::make('module.title')
                    ->label('Modul'),
                TextColumn::make('module.kelas.name')
                    ->label('Kelas'),
                TextColumn::make('module.kelas.user.name')
                    ->label('Dosen'),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn($state): string => match ($state) {
                        'pdf' => 'PDF',
                        'video' => 'Video',
                        'text' => 'Text',
                        'link' => 'Link',
                        default => ''
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pdf' => 'danger',
                        'video' => 'info',
                        'text' => 'gray',
                        'link' => 'warning',
                        default => 'secondary'
                    }),
                // TextColumn::make('content'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMateris::route('/'),
            'create' => Pages\CreateMateri::route('/create'),
            'edit' => Pages\EditMateri::route('/{record}/edit'),
            'view' => Pages\ViewMateri::route('/{record}'),
        ];
    }
}
