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

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('read-qr',  ['token' => $token])
        </div>
    </div>
</x-app-layout>
