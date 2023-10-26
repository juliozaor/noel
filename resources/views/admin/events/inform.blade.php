<x-app-layout>
    <x-slot name="header">       
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('inform-reservations', ['title' => 'Informe de reservas'])
        </div>
    </div>
</x-app-layout>
