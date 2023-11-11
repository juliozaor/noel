<div>
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-4 m-auto my-4 relative">
        <h1 class="text-2xl font-semibold mb-4">Escáner de Código QR</h1>
        <div id="qr-reader" class="mb-4" style="width: 100%">

        </div>
        <div id="spinner"
            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 rounded-lg"
            style="display: none">
            <div role="status">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-red-600"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <div class="flex space-x-2">
            <x-input type="text" id="QRtoken" wire:model.defer="qrcode"
                class="bg-gray-200 cursor-not-allowed w-100" readonly />
            {{-- <x-button id="findreservation" class="flex-1 bg-green-500 text-white rounded p-2"
                wire:click="getInformationQR">Buscar Reservacion</x-button> --}}
        </div>

    </div>
    @if (session()->has('message'))
        <div class="row">
            <div class="col pt-4">
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            </div>
        </div>
    @endif
    @if ($qrcode)
        @if ($reservation)
            @if ($isValid)
                <div class="card text-center">
                    <div class="card-header">
                        Validar entrada de <strong>{{ $user['name'] }}</strong>, con identificación
                        <strong>{{ $user['document'] }}</strong>
                    </div>
                    <div class="card-body">
                        @if (isset($this->reservation->reservation->programming->initial_date))
                            <div class="text-base">
                                Fecha Evento:
                                <b>{{ date('d-m-Y', strtotime($this->reservation->reservation->programming->initial_date)) }}</b>
                                Hora Evento:
                                <b>{{ date('h:i A', strtotime($this->reservation->reservation->programming->initial_time)) }}</b>
                            </div>
                            @if (date('d-m-Y', strtotime($this->reservation->reservation->programming->initial_date)) < date('d-m-Y'))
                                <svg class="h-20 w-20 text-custom-yellow-500  mx-auto" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                                <div class="text-base font-bold my-2">
                                    Este QR es de un evento que ya no está disponible
                                </div>
                            @elseif (date('d-m-Y', strtotime($this->reservation->reservation->programming->initial_date)) > date('d-m-Y'))
                                <svg class="h-20 w-20 text-indigo-600 mx-auto" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                                <div class="text-base font-bold my-2">
                                    Este QR es de un evento que aun no esta disponible
                                </div>
                            @else
                                <svg class="h-20 w-20 text-green-600  mx-auto" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                                <p class="card-text my-2">Al hacer clic en el siguiente botón, el codigo qr será deshabilitado</p>
                                <a href="#" class="btn btn-primary"
                                    wire:click="save({{ $reservation->id }})">Permitir
                                    Acceso</a>
                            @endif
                        @else
                            Evento Desconocido

                            <p class="card-text my-2">Al hacer clic en el siguiente botón, el codigo qr será deshabilitado</p>
                            <a href="#" class="btn btn-warn" wire:click="save({{ $reservation->id }})">Permitir
                                Acceso</a>
                        @endif


                    </div>
                </div>
            @else
                <div class="card text-center">
                    <div class="card-header">
                        Validando entrada de <strong>{{ $user['name'] }}</strong>, con identificación
                        <strong>{{ $user['document'] }}</strong>
                    </div>
                    <div class="card-body">
                        <svg class="h-20 w-20 text-custom-yellow-500 mx-auto" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <p class="card-text">Este QR ya fue leido el
                            {{ date('d-m-Y', strtotime($reservation->updated_at)) }} a las
                            {{ date('h:i A', strtotime($reservation->updated_at)) }}</p>
                    </div>
                </div>
            @endif
        @else
            <div class="card text-center">
                <div class="card-header">
                    Validando entrada QR errado
                </div>
                <div class="card-body">
                    <svg class="h-20 w-20 text-red-600  mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="card-text">Este QR no fue generado por nuestro sistema de acceso</p>
                </div>
            </div>
        @endif
    @else
        <div class="card text-center">
            <div class="card-header">
                Leyendo el QR
            </div>
            <div class="card-body">
                <svg class="h-20 w-20 text-custom-gray mx-auto" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <p class="card-text">Intenta leer el QR usando tu camara</p>
            </div>
        </div>
    @endif
    @push('js')
        <script src="https://unpkg.com/html5-qrcode"></script>
        <script>
            function docReady(fn) {
                if (document.readyState === "complete" || document.readyState === "interactive") {
                    setTimeout(fn, 1);
                } else {
                    document.addEventListener("DOMContentLoaded", fn);
                }
            }
            docReady(function() {
                var qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
                    const minEdgeSizeThreshold = 250;
                    const edgeSizePercentage = 0.75;
                    const minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                    const qrboxEdgeSize = Math.floor(minEdgeSize * edgeSizePercentage);

                    const size = Math.max(qrboxEdgeSize, minEdgeSizeThreshold);

                    return {
                        width: size,
                        height: size
                    };
                }


                let html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
                    fps: 10,
                    qrbox: qrboxFunction,
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: true
                });
                html5QrcodeScanner.render(onScanSuccess);

                function onScanSuccess(decodedText, decodedResult) {
                    let token = decodedText.split('/').pop();
                    document.getElementById('QRtoken').value = token;
                    @this.updateQrcodeValue(token);
                    @this.getInformationQR();
                    html5QrcodeScanner.pause();
                    document.getElementById('spinner').style.display = 'flex';
                }
                const observer = new MutationObserver((mutationsList, observer) => {
                    const updateButton = (id, params) => {
                        let button = document.getElementById(id);
                        if (button && button.textContent != params.text) {
                            button.textContent = params.text;
                            params.classes.forEach(element => {
                                button.classList.add(element);
                            });
                        }
                    }
                    mutationsList.forEach(mutation => {
                        const objectParams = [{
                                id: 'html5-qrcode-button-camera-permission',
                                text: 'Permitir cámara',
                                classes: ['bg-blue-500', 'rounded', 'text-white', 'p-2']
                            },
                            {
                                id: 'html5-qrcode-button-camera-start',
                                text: 'Iniciar Captura',
                                classes: ['bg-green-500', 'rounded', 'text-white', 'p-2']
                            },
                            {
                                id: 'html5-qrcode-button-camera-stop',
                                text: 'Detener Captura',
                                classes: ['bg-red-500', 'rounded', 'text-white', 'p-2']
                            }
                        ];
                        let addNode = mutation.addedNodes[0];
                        if (mutation.type == 'childList') {
                            if (['html5-qrcode-button-camera-permission',
                                    'html5-qrcode-button-camera-start',
                                    'html5-qrcode-button-camera-stop'
                                ].includes(addNode?.id)) {
                                updateButton(addNode?.id, objectParams.find(element => element.id ==
                                    addNode?.id));
                            }
                        } else if (mutation.type == 'attributes') {
                            if (['html5-qrcode-button-camera-permission',
                                    'html5-qrcode-button-camera-start',
                                    'html5-qrcode-button-camera-stop'
                                ].includes(mutation.target?.id)) {
                                updateButton(mutation.target?.id, objectParams.find(element => element
                                    .id == mutation.target?.id));
                            }
                        }
                    });

                });
                observer.observe(document.getElementById('qr-reader__dashboard'), {
                    attributes: true,
                    childList: true,
                    subtree: true
                });

                Livewire.on('ReservationInfoUpdated', (params) => {
                    console.log("🚀 ~ file: qr-scanner.blade.php:190 ~ Livewire.on ~ params:", params)
                    html5QrcodeScanner.render(onScanSuccess);
                });
            });
        </script>
    @endpush
</div>
