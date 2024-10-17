<?php

namespace App\Filament\Resources\CategoricalResource\Widgets;

use App\Models\Categorical;
use Filament\Widgets\Widget;
use App\Filament\Resources\CategoricalResource\Pages\ListCategoricals;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
class CustomChart extends Widget
{
    protected static string $view = 'filament.resources.categorical-resource.widgets.custom-chart';

    use InteractsWithPageTable;

    public ?Model $record = null;
    public $dataa;
    public $chart;
    protected function getTablePage(): string
    {
        return ListCategoricals::class;
    }

    // Override method getViewData untuk memberikan data ke view
    protected function getViewData(): array
    {
        $this->chart = Chartjs::build()
            ->name("tes")
            ->type("bar")
            ->size(["width" => 400, "height" => 200])
            ->labels(['Label x', 'Label y'])
            ->datasets([
                [
                    "label" => "My First dataset",
                    'backgroundColor' => ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                    'data' => [69, 59]
                ],
                [
                    "label" => "My First dataset",
                    'backgroundColor' => ['rgba(255, 99, 132, 0.3)', 'rgba(54, 162, 235, 0.3)'],
                    'data' => [65, 12]
                ]
            ])
            ->options([
                'scales' => [
                    "y" => [
                        "beginAtZero" => true
                        ]
                    ]
                ]);

        $this->dataa = json_encode($this->getChartData());
        return [
            'chartData' => $this->dataa,
            'chart' => $this->chart,
        ];
    }

    // Method untuk menyediakan data chart
    public function getChartData(): array
    {
        // Query all category models
        $categorical_records = Categorical::with('numerical')->get();

        // Initialize the datasets array
        $datasets = [];
        $labels = [];

        // Loop through each categorical record and prepare the datasets
        foreach ($categorical_records as $categorical_record) {
            $numerical_records = $categorical_record->numerical->sortBy('month');
            $num_fields = [];

            foreach ($numerical_records as $numerical_record) {
                $num_fields[] = $numerical_record->num_field; // Replace 'num_field' with the actual field name
            }

            $datasets[] = [
                'label' => $categorical_record->name,
                'data' => $num_fields,
            ];

            if (empty($labels)) {
                foreach ($numerical_records as $numerical_record) {
                    $labels[] = date("F", mktime(0, 0, 0, $numerical_record->month, 10)); 
                }
            }
        }
        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
}
