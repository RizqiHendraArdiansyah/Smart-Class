<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoalResource\Pages;
use App\Models\Kelas\KelasModel;
use App\Models\Modul\ModulModel;
use App\Models\Pertanyaan\PertanyaanModel;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SoalResource extends Resource
{
    protected static ?string $model = PertanyaanModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    // protected static ?string $navigationGroup = 'Materi';

    protected static ?string $navigationLabel = 'Soal';

    public static ?string $label = 'Soal';

    protected static ?string $pluralLabel = 'Daftar Soal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('class_id')
                    ->options(fn () => KelasModel::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                    )
                    ->required()
                    ->dehydrated(false)
                    ->searchable()
                    ->preload()
                    ->live()
                    ->columnSpanFull()
                    ->label('Kelas')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('module_id', null);
                        $set('quiz_id', null);
                    }),

                Select::make('module_id')
                    ->options(function (callable $get) {
                        if (! $get('class_id')) {
                            return [];
                        }

                        return ModulModel::query()
                            ->whereHas('kelas', function ($query) use ($get) {
                                $query->where('m_kelas.id', $get('class_id'));
                            })
                            ->pluck('title', 'id');
                    })
                    ->required()
                    ->dehydrated(false)
                    ->searchable()
                    ->preload()
                    ->live()
                    ->columnSpanFull()
                    ->label('Modul')
                    ->disabled(fn (callable $get) => ! $get('class_id'))
                    ->afterStateUpdated(fn (callable $set) => $set('quiz_id', null)),

                Select::make('quiz_id')
                    ->options(function (callable $get) {
                        if (! $get('module_id')) {
                            return [];
                        }

                        return \App\Models\Kuis\KuisModel::query()
                            ->where('module_id', $get('module_id'))
                            ->pluck('title', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->columnSpanFull()
                    ->disabled(fn (callable $get) => ! $get('module_id'))
                    ->label('Kuis')
                    ->afterStateHydrated(function ($state, callable $set, $record) {
                        if ($record && $record->kuis) {
                            $set('module_id', $record->kuis->module_id);
                            $kelas = $record->kuis->modul->kelas->first();
                            if ($kelas) {
                                $set('class_id', $kelas->id);
                            }
                        }
                    }),

                Textarea::make('text')
                    ->required()
                    ->columnSpanFull()
                    ->label('Pertanyaan'),

                Select::make('type')
                    ->columnSpanFull()
                    ->options([
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar/Salah',
                        // 'short_answer' => 'Jawaban Singkat',
                        // 'essay' => 'Essay',
                    ])
                    ->default('multiple_choice')
                    ->required()
                    ->live()
                    ->label('Tipe Soal'),

                // Pilihan Ganda Options
                Repeater::make('options')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('option')
                            ->required()
                            ->label('Pilihan'),
                    ])
                    ->defaultItems(4)
                    ->addable(false)
                    ->label('Pilihan Jawaban')
                    ->hidden(fn (Get $get): bool => $get('type') !== 'multiple_choice'),

                // Multiple Choice Answer
                Select::make('correct_answer')
                    ->columnSpanFull()
                    ->options([
                        'A' => 'Pilihan A',
                        'B' => 'Pilihan B',
                        'C' => 'Pilihan C',
                        'D' => 'Pilihan D',
                    ])
                    ->required()
                    ->label('Jawaban Benar')
                    ->hidden(fn (Get $get): bool => $get('type') !== 'multiple_choice'),

                // True/False Answer
                Radio::make('correct_answer')
                    ->columnSpanFull()
                    ->options([
                        'true' => 'Benar',
                        'false' => 'Salah',
                    ])
                    ->required()
                    ->inline()
                    ->label('Jawaban Benar')
                    ->hidden(fn (Get $get): bool => $get('type') !== 'true_false'),

                // Short Answer
                TextInput::make('correct_answer')
                    ->columnSpanFull()
                    ->required()
                    ->label('Jawaban Benar')
                    ->hidden(fn (Get $get): bool => ! in_array($get('type'), ['short_answer'])),

                // Essay - No correct answer needed
                TextInput::make('correct_answer')
                    ->columnSpanFull()
                    ->label('Panduan Penilaian (Opsional)')
                    ->hidden(fn (Get $get): bool => $get('type') !== 'essay'),

                Select::make('difficulty_level')
                    ->columnSpanFull()
                    ->options([
                        'easy' => 'Mudah',
                        'medium' => 'Sedang',
                        'hard' => 'Sulit',
                    ])
                    ->default('medium')
                    ->required()
                    ->label('Tingkat Kesulitan'),

                TextInput::make('points')
                    ->numeric()
                    ->default(10)
                    ->required()
                    ->suffix('Poin')
                    ->columnSpanFull()
                    ->label('Poin'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kuis.modul.kelas.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('kuis.modul.title')
                //     ->label('Modul')
                //     ->sortable()
                //     ->searchable(),
                Tables\Columns\TextColumn::make('kuis.title')
                    ->label('Kuis')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('text')
                    ->label('Pertanyaan')
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Soal')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'multiple_choice' => 'info', // Biru
                        'true_false' => 'success', // Hijau
                        'short_answer' => 'warning', // Kuning
                        'essay' => 'danger', // Merah
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar/Salah',
                        'short_answer' => 'Jawaban Singkat',
                        'essay' => 'Essay',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('difficulty_level')
                    ->label('Tingkat Kesulitan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'easy' => 'success', // Hijau
                        'medium' => 'warning', // Kuning
                        'hard' => 'danger', // Merah
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'easy' => 'Mudah',
                        'medium' => 'Sedang',
                        'hard' => 'Sulit',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('points')
                    ->label('Poin'),
            ])
            ->filters([
                // SelectFilter::make('class_id')
                //     ->relationship('kelas', 'name')
                //     ->label('Kelas'),
                // SelectFilter::make('module_id')
                //     ->relationship('modul', 'title')
                //     ->label('Modul'),
                SelectFilter::make('quiz_id')
                    ->relationship('kuis', 'title')
                    ->label('Kuis'),
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
            'index' => Pages\ListSoals::route('/'),
            'create' => Pages\CreateSoal::route('/create'),
            'edit' => Pages\EditSoal::route('/{record}/edit'),
        ];
    }
}
