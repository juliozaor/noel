<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="subtitulo d-flex justify-content-between">
                <div class="d-flex">
                   
                        <h6 class="me-4"> Lista de usuarios</h6>
                    
                    
                        <h6> Historial de Registros</h6>
                    
                </div>
                <div class="row">
                    @can('superAministrador')
                        <div class="col">
                            @livewire('bulk-load', ['title' => 'Carga masiva de colaboradores', 
                            'view' => 'collaborators', 'label'=> 'Colaboradores'])
                        </div>
                    @endcan
                    <div class="col">
                        @livewire('bulk-load', ['title' => 'Carga masiva de usuarios', 
                        'view' => 'users','label'=> 'Carga masiva'])
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('users', ['title' => 'Lista de usuarios'])
        </div>
    </div>
</x-app-layout>
