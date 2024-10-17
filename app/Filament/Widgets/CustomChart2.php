<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Widget;
use Filament\Support\RawJs;
use App\Filament\Resources\CategoricalResource\Pages\ListCategoricals;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class CustomChart2 extends ChartWidget
{
    use InteractsWithPageTable;
    protected static ?string $heading = 'Chart';
    public ?Model $record = null;
    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
            'datalabels' => [
                'align'=> 'end',
                'anchor' => 'start',
                'color' => '#059bff', 
                'font' => [
                    'weight' => 'bold',
                ],
            ],
        ],
    ];
    protected function getTablePage(): string
    {
        $list = ListCategoricals::class;
        return $list;
    }
    protected function getData(): array
    {   
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }
}
