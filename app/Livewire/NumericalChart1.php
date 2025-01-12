<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categorical;

class NumericalChart1 extends Component
{
    public function render()
    {
        // Query all categorCategorical models
        $categorical_records = Categorical::with('numerical')->get();

        // Initialize the datasets array
        $datasets = [];
        $labels = [];

        // Loop through each categorical record and prepare the datasets
        foreach ($categorical_records as $categorical_record) {
            $numerical_records = $categorical_record->numerical->sortBy('month');
            $type_values = [];

            foreach ($numerical_records as $numerical_record) {
                $type_values[] = $numerical_record->type_value; // Replace 'type_value' with the actual field name
            }

            $datasets[] = [
                'label' => $categorical_record->name,
                'data' => $type_values,
            ];

            if (empty($labels)) {
                foreach ($numerical_records as $numerical_record) {
                    $labels[] = date("F", mktime(0, 0, 0, $numerical_record->month, 10)); 
                }
            }
        }

        // Store the chart data
        $chartData = [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
        return view('livewire.numerical-chart1', compact("chartData"));
    }
}
