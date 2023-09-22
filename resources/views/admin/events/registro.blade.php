<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <h6> Registro de eventos</h6>
                </div>
                <div class="col-2 offset-md-3">
                    @livewire('create-waiting')
                </div>
                <div class="col-2 ">
                    <button class="btn btn-primary">Carga masiva</button>
                </div>
                <div class="col-2">
                    @livewire('create-user')
                </div>
                @livewire('create-reservation')
                @livewire('create-members')
                @livewire('register-user-in-event')
                
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('tablet-register', ['title' => 'Lista de registros a eventos'])
        </div>
    </div>
</x-app-layout>
