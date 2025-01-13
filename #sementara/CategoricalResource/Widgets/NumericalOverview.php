<?php

namespace App\Filament\Resources\CategoricalResource\Widgets;

use Filament\Support\RawJs;
use App\Filament\Resources\CategoricalResource\Pages\ListCategoricals;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class NumericalOverview extends ChartWidget
{
    use InteractsWithPageTable;
    protected static ?string $heading = 'Chart';

    public ?Model $record = null;

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
            ],
            'datalabels' => [
                'align' => 'top',
                'color' => '#FF0000', // Example: Red color for the labels
                'font' => [
                    'weight' => 'bold',
                ],
            ],
        ],
    ];

    protected function getTablePage(): string
    {
        return ListCategoricals::class;
    }

    protected function getData(): array
    {
        $categorical_records = $this->getPageTableRecords();

        // Initialize the datasets array
        $datasets = [];
        $labels = [];

        // Loop through each categorical record and prepare the datasets
        foreach ($categorical_records as $categorical_record) {
            $numerical_records = $categorical_record->numerical;
            $numerical_records = $numerical_records->sortBy('month');
            $num_fields = [];
            
            foreach ($numerical_records as $numerical_record) {
                $num_fields[] = $numerical_record->num_field; // Replace 'num_field' with actual field name
            }

            $datasets[] = [
                'label' => $categorical_record->name, // Label for each categorical record
                'data' => $num_fields, // Data points (num_field values)
                // 'backgroundColor' => $this->generateColor($categorical_record->name), // You can make this dynamic per category if needed
                // 'borderColor' => $this->generateColor($categorical_record->name), // You can make this dynamic per category if needed
            ];
            
            // Optionally, you can collect labels based on specific criteria (e.g., month names)
            if (empty($labels)) {
                // Assuming all categorical records share the same period labels, collect them from one record
                foreach ($numerical_records as $numerical_record) {
                    $labels[] = date("F", mktime(0, 0, 0, $numerical_record->month, 10)); ; // Assuming numerical records have a 'month' attribute
                }
            }
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function generateColor(string $string): string
    {
        // Generate a color based on the hash of the string
        return '#' . substr(md5($string), 0, 6);
    }

    protected function getType(): string
    {
        return 'line';
    }
}
