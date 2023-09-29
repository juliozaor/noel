<div>

    <x-dialog-modal-j wire:model="openMembers">
        <x-slot name="title">Nuevo registro/ Registrar miembros asistentes <span class="font-medium text-red-600">- Todos
                los campos son obligatorios

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
            </div>

        </x-slot>



        <x-slot name="footer" class="bg-success">
            <x-secondary-button wire:click=openModalCreateReservation>
                Atrás
            </x-secondary-button>
            <x-danger-button wire:click=resetDatesInAll>
                Cancelar registro
            </x-danger-button>
            <x-danger-button wire:click=validateMembers>
                Continuar
            </x-danger-button>
        </x-slot>
    </x-dialog-modal-j>

    @push('js')
        {{--  <script src="sweetalert2.all.min.js"></script>

        <script>
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        </script> --}}
    @endpush
</div>
