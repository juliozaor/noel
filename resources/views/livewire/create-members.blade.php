<div>

    <x-dialog-modal-j wire:model="openMembers">
        <x-slot name="title">Nuevo registro/ Registrar miembros asistentes <span class="font-medium text-red-600">- Todos los campos son obligatorios

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
                    <x-input type="text" class="w-100" wire:model.defer="name" disabled/>
                    <x-input-error for="name" />
    
                </div>
                <div class="mr-2 w-50">
                    <x-label value="Número de documento" />
                    <x-input type="number" class="w-100" wire:model.defer="document" disabled/>
                    <x-input-error for="document" />
                </div>
            </div>
            </div>
        </div>
        <div class="scroll-container">
       
            @foreach (range(2, $quota) as $index)
        {{-- <input wire:model="data.{{ $index }}" type="text"> --}}
        <div class="mt-2">
            <span class="sub-titulo-miembro px-6 ps-4 pe-4 ">Miembro {{ $index }}</span>            
        <div class="mt-2 px-6 ps-4 pe-4">
            <div class="d-flex datos-miembro pb-4 pt-2">
            <div class="mr-2 w-50">
                <x-label value="Nombre completo" />
                <x-input type="text" class="w-100" wire:model.defer="nameMember.{{ $index }}"/>
                <x-input-error for="nameMember.{{ $index }}" />

            </div>
            <div class="mr-2 w-25">
                <x-label value="Número de documento" />
                <x-input type="number" class="w-100" wire:model.defer="documentMember.{{ $index }}"/>
                <x-input-error for="documentMember.{{ $index }}" />
            </div>
            <div class="mr-2 w-25">
                <x-label value="¿Es menor de edad" />
                <div class="mt-2 ">
                    Si <input wire:model="minor.{{ $index }}" name="minor.{{ $index }}" type="radio" value="1" class="mr-2" checked />
                    No <input wire:model="minor.{{ $index }}" name="minor.{{ $index }}" type="radio" value="0" />
                </div>
            </div>
            </div>
        </div>
    </div>
        
    @endforeach
</div>
{{-- 
            <div class="d-flex pt-4 px-6 ps-4 pe-4 ">
                <div class="mr-2 w-25">
                    <x-label value="Documento de identidad" />
                    <x-input type="text" class="w-100" wire:model.defer="document" wire:blur="searchProfile" />
                    <x-input-error for="document" />

                </div>
                <div class="mr-2 w-25">
                    <x-label value="Nombre Completo" />
                    <x-input type="text" class="w-100" wire:model.defer="name" />
                    <x-input-error for="name" />
                </div>
                <div class="mr-2 w-25">
                    <x-label value="Celular" />
                    <x-input type="text" class="w-100" wire:model.defer="cell" />
                    <x-input-error for="cell" />
                </div>
            </div>

            <div class="d-flex pt-4 px-6 ps-4 pe-4 ">
                <div class="mr-2 w-25">
                    <x-label value="Barrio" />
                    <x-input type="text" class="w-100" wire:model.defer="neighborhood" />
                    <x-input-error for="neighborhood" />
                </div>
                <div class="mr-2 w-25">
                    <x-label value="Fecha de nacimiento" />
                    <x-input type="date" class="w-100" wire:model.defer="birth" />
                    <x-input-error for="birth" />
                </div>
                <div class="mr-2 w-25">
                    <x-label value="Correo electrónico empresarial" />
                    <x-input type="text" class="w-100" wire:model.defer="email" />
                    <x-input-error for="email" />
                </div>
                <div class="mr-2 w-25">
                    <x-label value="EPS" />
                    <div class="mt-2 ">
                        Si <input wire:model="epsState" name="epsState" type="radio" value="1" class="mr-2" />
                        No <input wire:model="epsState" name="epsState" type="radio" value="0" />
                    </div>
                </div>
            </div>

            <div class="d-flex pt-4 px-6 ps-4 pe-4 ">
                <div class="mr-2 w-25">
                    <x-label value="¿Cuál?" />
                    <input type="text"
                        class="w-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        wire:model.defer="eps" @if ($epsState !== '1') disabled @endif />
                    <x-input-error for="eps" />
                </div>
                <div class="mr-2 w-25">
                    <x-label value="¿Estuviste en nuestra Experiencia 2022?" />
                    <div class="mt-2">
                        Si <input wire:model.defer="experience2022" name="experience2022" type="radio" value="1"
                            class="mr-2" />
                        No <input wire:model.defer="experience2022" name="experience2022" type="radio"
                            value="0" />
                    </div>
                </div>
                <div class="mr-2 w-25">
                    <x-label value="¿Como te enteraste de este evento?" />
             
                    <select wire:model="reference" class="form-control">
                        <option value="1">Administrador</option>
                        <option value="2">Otro</option>

                    </select>
                    <x-input-error for="reference" />
                </div>
                <div class="mr-2 w-25">

                </div>

            </div> --}}



        </x-slot>



        <x-slot name="footer" class="bg-success">
            <x-secondary-button wire:click=openModalCreateReservation>
                Atrás
            </x-secondary-button>
            <x-danger-button wire:click=save>
                Continuar
            </x-danger-button>

        </x-slot>
    </x-dialog-modal-j>
</div>
