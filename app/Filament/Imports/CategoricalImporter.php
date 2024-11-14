<?php

namespace App\Filament\Imports;

use App\Models\Categorical;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CategoricalImporter extends Importer
{
    protected static ?string $model = Categorical::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('project_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('pj_blok')
                ->requiredMapping()
                ->rules(['max:255']),
            ImportColumn::make('area')
                ->requiredMapping()
                ->castStateUsing(function (float $state): ?float {
                    if (blank($state)) {
                        return null;
                    }
                    
                    $state = floatval($state);
                
                    return round($state, precision: 2);
                })
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('num_tree')
                ->requiredMapping()
                ->castStateUsing(function (int $state): ?float {
                    if (blank($state)) {
                        return null;
                    }
                    
                    $state = intval($state);
                
                    return $state;
                })
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('is_research')
                ->requiredMapping()
                ->castStateUsing(function (bool $state): ?float {
                    if (blank($state)) {
                        return null;
                    }
                    $state = intval($state) ? true : false;
                
                    return $state;
                })
                ->boolean()
                ->rules(['boolean']),
            ImportColumn::make('is_panen')
                ->requiredMapping()
                ->castStateUsing(function (bool $state): ?float {
                    if (blank($state)) {
                        return null;
                    }
                    $state = intval($state) ? true : false;
                
                    return $state;
                })
                ->boolean()
                ->rules(['boolean']),
            ImportColumn::make('is_pupuk')
                ->requiredMapping()
                ->castStateUsing(function (bool $state): ?float {
                    if (blank($state)) {
                        return null;
                    }
                    $state = intval($state) ? true : false;
                
                    return $state;
                })
                ->boolean()
                ->rules(['boolean']),
        ];
    }

    public function resolveRecord(): ?Categorical
    {
        // return Categorical::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Categorical();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your categorical import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
