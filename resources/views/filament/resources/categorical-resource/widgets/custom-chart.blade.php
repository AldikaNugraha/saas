<x-filament-widgets::widget>
    <x-filament::section>
        <h1>Ini custom chart</h1>
        <canvas id="myChart"></canvas>
        {{-- @dd($chartData) --}}
        @assets
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        @endassets
        @script
        <script>
            document.addEventListener('livewire:navigated', function () {
                let chartData = JSON.parse($wire.$get('dataa'))
                console.log(typeof chartData)
                console.log(chartData)
    
                let labels = chartData.labels;
                let datasets = chartData.datasets;
    
                console.log('Labels:', labels);
                console.log('Datasets:', datasets);
                const data = {
                    labels: labels,
                    datasets: datasets,
                };
                const config = {
                    type: 'bar',
                    data: data,
                    options: {}
                };
                var ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(
                    ctx,
                    config
                );
            })
        </script>
        @endscript
    </x-filament::section>
</x-filament-widgets::widget>
