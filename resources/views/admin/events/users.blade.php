<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-md-1 mt-2">
                </div>
                <div class="col-md-3 mt-2">
                </div>
                <div class="col-md-2 mt-2">
                    @can('superAministrador')
                        <a href="{{ route('admin.export') }}" class="btn botonRojo">Enviar correo</a>
                    @endcan
                </div>
                <div class="col-md-2 mt-2">
                    @can('superAministrador')
                        <a href="{{ route('admin.download') }}" class="btn botonRojo">Descargar</a>
                    @endcan
                </div>
                <div class="col-md-2 mt-2">
                    @can('superAministrador')
                        @livewire('bulk-load', ['title' => 'Carga masiva de colaboradores', 'view' => 'collaborators', 'label' => 'Colaboradores'])
                    @endcan
                </div>
                <div class="col-md-2 mt-2">
                    @livewire('bulk-load', ['title' => 'Carga masiva de usuarios', 'view' => 'users', 'label' => 'Carga masiva'])
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
