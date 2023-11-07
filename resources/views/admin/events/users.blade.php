<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row justify-end">
                <div class="col-md-2 mt-2">
                    @can('superAministrador')
                        @livewire('bulk-load', ['title' => 'Carga masiva de colaboradores', 'view' => 'collaborators', 'label' => 'Colaboradores'])
                    @endcan
                </div>
                <div class="col-md-2 mt-2">
                    @livewire('bulk-load', ['title' => 'Carga masiva de usuarios', 'view' => 'users', 'label' => 'Carga masiva'])
                </div>
                @livewire('create-reservation')
                @livewire('create-members')
                @livewire('register-user-in-event')

            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('users', ['title' => 'Lista de usuarios'])
        </div>
      
    </div>
</x-app-layout>
