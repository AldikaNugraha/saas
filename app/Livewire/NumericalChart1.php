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

        // Store the chart data
        $chartData = [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
        return view('livewire.numerical-chart1', compact("chartData"));
    }
}
