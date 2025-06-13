<?php

namespace App\Filament\Widgets;

use App\Models\Kuis\KuisModel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestQuizzes extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Kuis Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                KuisModel::query()
                    ->whereDate('deadline', '>=', now())
                    ->orderBy('deadline')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Kuis'),
                TextColumn::make('module.kelas.name')
                    ->label('Kelas'),
                TextColumn::make('deadline')
                    ->label('Deadline')
                    ->dateTime('d F Y'),
            ]);
    }
}
