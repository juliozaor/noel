<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-md-1 mt-2">
                </div>
                <div class="col-md-2 mt-2">
                </div>

            </div>
        </div>
    </x-slot>

    <div class="flex items-center justify-center h-screen">
        <div class="text-center">
            <h1 class="text-3xl font-bold mb-4">Bienvenido</h1>
            <div class="bg-white rounded-lg shadow-lg p-2 text-center">
                <div class="font-bold" style="font-size: 80px;">{{$numeroDeVisitas}}</div>
                <div class="text-base font-semibold">Contador de Invitados</div>
            </div>
        </div>
    </div>
</x-app-layout>
