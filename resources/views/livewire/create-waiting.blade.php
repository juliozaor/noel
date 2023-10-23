<div>
    <x-button class="botonRojo" wire:click="$set('openNewRegister', true)">
        Registrar lista de espera
    </x-button>


    <x-dialog-modal-j wire:model="openNewRegister">
        <x-slot name="title">
            Nuevo registro <span class="font-medium text-red-600">- Todos los campos son obligatorios</span>

        </x-slot>
        <x-slot name="content">

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
                <div class="mr-2 w-25">
                    <x-label value="Dirección" />
                    <x-input type="text" class="w-100" wire:model.defer="address" />
                    <x-input-error for="address" />
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
                    {{-- <x-input type="date" class="w-100" wire:model.defer="reference" /> --}}
                    <select wire:model="reference" class="form-control">
                        <option value="1">Redes Sociales de la Compañia</option>
                        <option value="2">Amigo o Familia</option>
                        <option value="3">Búsqueda en Internet</option>
                        <option value="4">Medios de comunicación y Publicidad</option>
                        <option value="5">Otro</option>

                    </select>
                    <x-input-error for="reference" />
                </div>
                <div class="mr-2 w-25">

                </div>

            </div>

            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

        </x-slot>



        <x-slot name="footer" class="bg-success">
            <x-secondary-button wire:click=close>
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click=saveUpdateContinue>
                Continuar
            </x-danger-button>

        </x-slot>
    </x-dialog-modal-j>
</div>
