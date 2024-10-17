<div>
    <div>
        
    </div>
</div>
@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
@endassets
@script
    <script>
        document.addEventListener('livewire:init', function () {

            let components = Livewire.all()
            console.log(components)
        })
    </script>
@endscript