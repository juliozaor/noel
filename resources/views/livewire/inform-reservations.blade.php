<div>
    <div class="container mb-10">
        <span>Seleccionar evento</span>
        <div class="row d-flex mt-3 align-items-end">
            <div class="col-3">
                <x-label value="Fecha evento" />
                <x-input type="date" class="w-100" wire:model.defer="selectedDate" id="selectedDate" />
                <x-input-error for="date" />
            </div>
            <div class="col-2">
                <x-button class="botonAzul" wire:click="getChartData">
                    Consultar
                </x-button>
            </div>
        </div>
    </div>

    <div class="contarner">
        @if (session()->has('message'))
        <div class="row">
            <div class="col pt-4">
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col">
                @if ($estadisticasMes)
                <h2 class="text-xl font-semibold m-4">Estadísticas Generales - {{ \Carbon\Carbon::parse($selectedDate)->locale('es-ES')->format('F'); }}</h2>
                <div class="flex flex-wrap -mx-4  mt-10">
                    <!-- Card 1 -->
                    <div class="w-full md:w-1/2 lg:w-1/4 xl:w-1/6 px-1 mb-4">
                        <div class="bg-white rounded-lg shadow-lg p-2 text-center">
                            <div class="text-3xl font-bold">{{$estadisticasMes['Cupos_Disponibles']}}</div>
                            <div class="text-base font-semibold">Cupos Disponibles</div>
                        </div>
                    </div>
                
                    <!-- Card 2 -->
                    <div class="w-full md:w-1/2 lg:w-1/4 xl:w-1/6 px-1 mb-4">
                        <div class="bg-white rounded-lg shadow-lg p-2 text-center">
                            <div class="text-3xl font-bold">{{$estadisticasMes['Personas_Registradas']}}</div>
                            <div class="text-base font-semibold">Personas Registradas</div>
                        </div>
                    </div>
                
                    <!-- Card 3 -->
                    <div class="w-full md:w-1/2 lg:w-1/4 xl:w-1/6 px-1 mb-4">
                        <div class="bg-white rounded-lg shadow-lg p-2 text-center">
                            <div class="text-3xl font-bold">{{$estadisticasMes['Reservaciones']}}</div>
                            <div class="text-base font-semibold">Reservaciones</div>
                            <div class="text-sm">*(solo titulares)</div>
                        </div>
                    </div>
                    
                    <!-- Card 4 -->
                    <div class="w-full md:w-1/2 lg:w-1/4 xl:w-1/6 px-1 mb-4">
                        <div class="bg-white rounded-lg shadow-lg p-2 text-center">
                            <div class="text-3xl font-bold">{{$estadisticasMes['Cupos_Reservados']}}</div>
                            <div class="text-base font-semibold">Cupos Reservados</div>
                            <div class="text-sm">*(incluye miembros)</div>
                        </div>
                    </div>

                     <!-- Card 5 -->
                     <div class="w-full md:w-1/2 lg:w-1/4 xl:w-1/6 px-1 mb-4">
                        <div class="bg-white rounded-lg shadow-lg p-2 text-center">
                            <div class="text-3xl font-bold">{{$estadisticasMes['Lista_Espera']}}</div>
                            <div class="text-base font-semibold">Lista de Espera</div>
                        </div>
                    </div>

                    <!-- Card 6-->
                    <div class="w-full md:w-1/2 lg:w-1/4 xl:w-1/6 px-1 mb-4">
                        <div class="bg-white rounded-lg shadow-lg p-2 text-center">
                            <div class="text-3xl font-bold">{{$estadisticasMes['Asistencias']}}</div>
                            <div class="text-base font-semibold">Asistencias</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col">
                @if ($estadisticasDia)
                <h2 class="text-xl font-semibold mb-4">Estadísticas Reservas - Fecha: {{ $selectedDate }}</h2>
                <canvas id="barChart2"></canvas>
                @endif
            </div>
        </div>
       
        @endif
    </div>
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
        <script>
        let myChart = null; 
        Livewire.on('chartDataUpdated', params => {
            this.drawsecondGraph(params,'barChart2');
        });
        function drawsecondGraph(params,container){
            const ctx = document.getElementById(container).getContext('2d');

            const statics = params.estadisticasDia;
            const labels = statics.map(data => data.initial_time); // Etiquetas de horas
            const qrCodesData = statics.map(data => data.qr_codes_count);
            const quotaAvailableData = statics.map(data => data.quota_available);
            const reservationCountData = statics.map(data => data.reservation_count);
            const reservationSumData = statics.map(data => data.reservation_sum_quota);
            // Define los datos y etiquetas para la gráfica
            const data = {
            labels: labels,
            datasets: [
                {
                label: 'Cupos Disponibles',
                data: quotaAvailableData,
                backgroundColor: 'rgba(37, 135, 36, 0.2)',
                borderColor: 'rgba(237, 135, 36, 0.2)',
                borderWidth: 1,
                stack: 'stack 0'
                },
                {
                label: 'Cupos Reservados',
                data: reservationSumData,
                backgroundColor:'rgba(37, 135, 36, 1)',
                borderColor: 'rgba(37, 135, 36, 1)',
                borderWidth: 1,
                stack: 'stack 0'
                },
                {
                label: 'Asistentes',
                data: qrCodesData,
                backgroundColor: 'rgba(238, 40, 37, 1)',
                borderColor: 'rgba(238, 40, 37, 1)',
                borderWidth: 1,
                stack: 'stack 1'
                }
            ]
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
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'Estadísticas de reservas'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        </script>
    @endpush
</div>
