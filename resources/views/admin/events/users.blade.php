<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-md-1 mt-2">
                    <h6>Lista de usuarios</h6>
                </div>
                <div class="col-md-2 mt-2">
                    <h6> Historial de Registros </h6>
                </div>
                <div class="col-md-2 mt-2 offset-md-5">
                    @can('superAministrador')
                        @livewire('bulk-load', ['title' => 'Carga masiva de colaboradores', 
                        'view' => 'collaborators', 'label'=> 'Colaboradores'])
                @endcan
                </div>
                <div class="col-md-2 mt-2">
                    @livewire('bulk-load', ['title' => 'Carga masiva de usuarios', 
                        'view' => 'users','label'=> 'Carga masiva'])
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
