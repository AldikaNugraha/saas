<?php

namespace App\Filament\Resources\VectorResource\Pages;

use App\Filament\Resources\VectorResource;
use App\Models\Numerical;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables\Table;

class ViewVectorNumericals extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithRecord;
    public ?int $categorical_id = null;
    protected static string $resource = VectorResource::class;
    protected static string $view = 'filament.resources.vector-resource.pages.view-vector-numericals';
    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->categorical_id = request()->query('categorical_id');
    }
    // dd($this->record->categorical()->get()[0]->numerical()->get()[0]);
    public function table(Table $table): Table
    {   
        return $table
            ->query(Numerical::query()->where('categorical_id', $this->categorical_id))
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
