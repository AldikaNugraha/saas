<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Filament\Resources\VectorResource;
use Filament\Resources\Pages\Page;
use App\Models\Categorical;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class ViewVectorProperties extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithRecord;
    protected static string $resource = VectorResource::class;

    protected static string $view = 'filament.resources.vector-resource.pages.view-vector-properties';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Categorical::query()->where('vector_id', $this->record->id))
            ->columns([
                TextColumn::make('name')
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('view_numericals')
                    ->url(fn (Categorical $record): string => 
                        ViewVectorNumericals::getUrl(['record' => $this->record->id]) . '?categorical_id=' . $record->id
                        )
                    ->label('View Numericals')
            ])
            ->bulkActions([
                // ...
            ]);
    }
}

