<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="row">
                <div class="col-1">
                    <h6> Leer QR</h6>
                </div>
                <div class="col-2">
                    <h6> Leer codigo QR </h6>
                </div>

            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('read-qr',  ['token' => $token])
        </div>
    </div>
</x-app-layout>
