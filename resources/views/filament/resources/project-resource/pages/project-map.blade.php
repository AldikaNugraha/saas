<x-filament-panels::page>
    <div class="w-full h-full position-absolute top-0 left-0">
        <iframe 
            src="http://127.0.0.1:5001/iframe?token={{ env('FLASK_API_TOKEN') }}" 
            width="1100" 
            height="800" 
            style="border:none;">
        </iframe>
    </div>
</x-filament-panels::page>
