<div>

    <x-dialog-modal-j wire:model="openMembers">
        <x-slot name="title">Nuevo registro/ Registrar miembros asistentes <span class="font-medium text-red-600">- Todos
                los campos son obligatorios
<br>

            </span>

        </x-slot>
        <x-slot name="content">
            <div class="d-flex pt-4 px-6 ps-4 pe-4 ">
                <div class="mensajeMembers p-4">
                    <strong>A continuación agrega los miembros de tu familia</strong><br>
                    Agrega la información de los miembros de tu familia que irán a este evento
                </div>
            </div>

            <div class="mt-2">
                <span class="sub-titulo-miembro px-6 ps-4 pe-4 ">Miembro 1 (titular de la reserva)</span>

                <div class="mt-2 px-6 ps-4 pe-4 ">
                    <div class="d-flex datos-miembro pb-4 pt-2">
                        <div class="mr-2 w-50">
                            <x-label value="Nombre completo" />
                            <x-input type="text" class="w-100" wire:model.defer="name" disabled />
                            <x-input-error for="name" />

                        </div>
                        <div class="mr-2 w-50">
                            <x-label value="Número de documento" />
                            <x-input type="number" class="w-100" wire:model.defer="document" disabled />
                            <x-input-error for="document" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="scroll-container">

@if ($quota > 1)

                @foreach (range(2, $quota) as $index)
                    <div class="mt-2">
                        <span class="sub-titulo-miembro px-6 ps-4 pe-4 ">Miembro {{ $index - 2 }}</span>
                        <div class="mt-2 px-6 ps-4 pe-4">
                            <div class="d-flex datos-miembro pb-4 pt-2">
                                <div class="mr-2 w-50 ">
                                    <x-label value="Nombre completo" />
                                    <x-input type="text" class="w-100"
                                        wire:model.defer="nameMember.{{ $index - 2 }}" />
                                    <x-input-error for="nameMember.{{ $index - 2 }}" />

                                </div>
                                <div class="mr-2 w-25">
                                    <x-label value="Número de documento" />
                                    <x-input type="number" class="w-100"
                                        wire:model.defer="documentMember.{{ $index - 2 }}" />
                                    <x-input-error for="documentMember.{{ $index - 2 }}" />
                                </div>
                                <div class="mr-2 w-25">
                                    <x-label value="¿Es menor de edad" />
                                    <div class="mt-2 ">
                                        Si <input wire:model="minor.{{ $index - 2 }}"
                                            name="minor.{{ $index - 2 }}" type="radio" value="1"
                                            class="mr-2" checked />
                                        No <input wire:model="minor.{{ $index - 2 }}"
                                            name="minor.{{ $index - 2 }}" type="radio" value="0" />
                                    </div>
                                </div>
                                @if ($editReservation)
                                    <div class="form-check mr-2 w-25 d-flex align-items-center">
                                        <input class="form-check-input mr-2" type="checkbox"
                                            wire:model="notAttend.{{ $index - 2 }}">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            No asistirá
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                @endif
            </div>
            @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        </x-slot>



        <x-slot name="footer" class="bg-success">
            <x-secondary-button wire:click=openModalCreateReservation>
                Atrás
            </x-secondary-button>
            <x-danger-button wire:click=delectRegister>
                Cancelar registro
            </x-danger-button>
            <x-danger-button wire:click=validateMembers>
                Continuar
            </x-danger-button>
        </x-slot>
    </x-dialog-modal-j>

    @push('js')
        <script src="sweetalert2.all.min.js"></script>
        <script>
            Livewire.on('delReservation', reservationId => {
                Swal.fire({
                    title: 'Se eliminará la reserva',
                    text: "¿Esta seguro de eliminar la reserva?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('create-members','confirmDeletereservation',reservationId)
                        Swal.fire(
                            'Eliminado!',
                            'la reserva ha sido eliminada.',
                            'success'
                        )
                    }
                });

            });
        </script>
        <script>
                Livewire.on('reiniciar', function () {
                    location.reload();
                });
        </script>
    @endpush
</div>
