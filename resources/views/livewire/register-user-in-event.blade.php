<div>
    <x-dialog-modal-j wire:model="openRegisterUserInEvent" >
        <x-slot name="title">
            <div class="d-flex justify-content-between">
                <span>Registrar usuario en evento</span>
               
                <button wire:click="$set('openRegisterUserInEvent', false)" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="encabezadoRegistro">
                <div class="d-flex w-50 pt-4 px-6 pb-4">
                    <div class="mr-2 w-25">
                        <strong>Fecha:</strong><br>
                        <span>{{$date}}</span>
                    </div>
                    <div class="mr-2 w-25">
                        <strong>Hora:</strong><br>
                        <span>{{ \Carbon\Carbon::parse($time)->format('h:i A')}}</span>
                    </div>
                    <div class="mr-2 w-25">
                        <strong>Cupos totales:</strong><br>
                        <span>{{$quota}}</span>
                    </div>
                    <div class="mr-2 w-25">
                        <strong>Cupos disponibles:</strong><br>
                        <span>{{$quotaAvailable}}</span>
                    </div>
                </div>
            </div>

            <ul class="nav nav-pills encabezadoTabs mb-3 pt-4 px-6" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                        aria-selected="true">Lista de usuarios</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                        aria-selected="false" >Lista de espera</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                        aria-selected="false">Usuarios registrados</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    @livewire('list-users')
                    
                </div>

                {{-- Lista de espera --}}
                
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    @livewire('list-waits')
                
                </div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @livewire('list-users-register')
                </div>
            </div>







        </x-slot>

        <x-slot name="footer" class="bg-success">
        </x-slot>
    </x-dialog-modal-j>
    <script>
        function changeCantUser(event) {
            const newValue = event.target.value;
            Livewire.emit('changeCantUser', newValue);
        }
        function changeCantUserWait(event) {
            const newValue = event.target.value;
            Livewire.emit('changeCantUserWait', newValue);
        }
        document.addEventListener('livewire:load', function () {
        Livewire.on('modalOpened', (targetTabId) => {
            let targetTabButton = document.querySelector('[data-bs-target="'+ targetTabId +'"]');
            if (targetTabButton) {
            targetTabButton.click();
        }
    });
});

/* document.addEventListener('DOMContentLoaded', function () {
    let tabs = document.querySelectorAll('.nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            let activeTab = document.querySelector('.nav-link.active');
            alert(activeTab.textContent);
        });
    });
}); */
    </script>
</div>
