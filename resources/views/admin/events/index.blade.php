<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-1">
                    <h6> Eventos</h6>
                </div>
                <div class="col-2">
                    <h6> Historial de Registros </h6>
                </div>
                <div class="col-2 offset-md-5">
                    @livewire('bulk-load', ['title' => 'Carga masiva de eventos', 
                    'view' => 'programmings','label'=> 'Carga masiva'])
                </div>
                <div class="col-2">
                    @livewire('create-programming')
                </div>

            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('tablet-programming', ['title' => 'Lista de eventos'])
        </div>
    </div>
</x-app-layout>
