<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-md-2 mt-2">
                 
                </div>
                <div class="col-md-3 mt-2 offset-md-5">
                    @livewire('create-waiting')
                </div>
                <div class="col-md-2 mt-2">
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
