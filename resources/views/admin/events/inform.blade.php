<x-app-layout>
    <x-slot name="header">
        <div class="container flex justify-end">
            <div class="mr-2 mt-2">
                @can('superAministrador')
                    <a href="{{ route('admin.downloadInform') }}" class="btn botonRojo" target="_blank">Descargar Informe General</a>
                @endcan
            </div>
            <div class="mr-2 mt-2">
                @can('superAministrador')
                    <a href="{{ route('admin.sendReport') }}" class="btn botonRojo" target="_blank">Enviar correo Reservas</a>
                @endcan
            </div>
            <div class="mt-2">
                @can('superAministrador')
                    <a href="{{ route('admin.downloadDetail') }}" class="btn botonRojo" target="_blank">Descargar Exporte Reservas</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('inform-reservations', ['title' => 'Informe de reservas'])
        </div>
    </div>
</x-app-layout>
