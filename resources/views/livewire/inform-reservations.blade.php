<div>
    <div class="container">
        <span>Seleccionar evento</span>
        <div class="row d-flex mt-3 align-items-end">
            <div class="col-3">
                <x-label value="Fecha evento" />
                <x-input type="date" class="w-100" wire:model.defer="selectedDate" id="selectedDate" />
                <x-input-error for="date" />
            </div>
            <div class="col-3">
                <x-label value="Horario" />
            <x-input type="time" class="w-100" wire:model.defer="selectedTime" id="selectedTime" />
            <x-input-error for="time" />
            </div>
            <div class="col-2">
                <x-button class="botonAzul" wire:click="getChartData">
                    Consultar
                </x-button>
            </div>
        </div>
    </div>

    <div class="contarner">
        <div class="row">
            <div class="col">
                <canvas id="barChart"></canvas>
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

            </div>
        </div>
    </div>


    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
        <script>
          Livewire.on('chartDataUpdated', params => {
    var ctx = document.getElementById('barChart').getContext('2d');
    var myChart = null; // Inicializa la variable myChart

    // Obtiene los datos actualizados de Livewire
    var cuposTotales = params.cuposTotales;
    var cuposReservados = params.cuposReservados;
    var cuposConfirmados = params.cuposConfirmados;

    // Define los datos y etiquetas para la gráfica
    var data = {
        labels: ['Cupos Totales', 'Cupos Reservados', 'Cupos Confirmados'],
        datasets: [{
            label: 'Reservas',
            data: [cuposTotales, cuposReservados, cuposConfirmados],
            backgroundColor: [
                'rgba(238, 40, 37, 1)',
                'rgba(37, 135, 36, 1)',
                'rgba(255, 84, 84, 0.2)'
            ],
            borderColor: [
                'rgba(238, 40, 37, 1)',
                'rgba(37, 135, 36, 1)',
                'rgba(255, 84, 84, 0.2)'
            ],
            borderWidth: 1
        }]
    };

    // Destruye la instancia de la gráfica si existe
    if (myChart) {
        myChart.destroy();
    }

    // Crea una nueva instancia de la gráfica
    myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
        </script>
    @endpush
</div>
