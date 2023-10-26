<div>
    <div>
        <label for="selectedDate">Fecha:</label>
        <input type="date" wire:model="selectedDate" id="selectedDate">
    </div>
    
    <div>
        <label for="selectedTime">Hora:</label>
        <input type="time" wire:model="selectedTime" id="selectedTime">
    </div>
    
    <div>
        <canvas id="barChart"></canvas>
    </div>
    
    <button wire:click="getChartData">Consultar</button>
    
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<script>
    document.addEventListener('livewire:load', function () {
        var ctx = document.getElementById('barChart').getContext('2d');
        var myChart;

Livewire.on('chartDataUpdated', function() {
    alert("entro");
    // Obtiene los datos actualizados de Livewire
    var cuposTotales = @json($cuposTotales);
    var cuposReservados = @json($cuposReservados);
    var cuposConfirmados = @json($cuposConfirmados);

    // Define los datos y etiquetas para la gráfica
    var data = {
        labels: ['Cupos Totales', 'Cupos Reservados', 'Cupos Confirmados'],
        datasets: [{
            data: [cuposTotales, cuposReservados, cuposConfirmados],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Actualiza la gráfica si ya existe
    if (myChart) {
        myChart.data = data;
        myChart.update();
    } else { // Crea una nueva gráfica si no existe
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
    }
});
</script>