<div>
    <x-button wire:click="$set('open', true)">
        Crear evento
    </x-button>


    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Crear nuevo evento
        </x-slot>

        <x-slot name="content">
            <input type="hidden" wire:model.defer="eventId">
            <div class="mb-3">
                <x-label value="Nombre del evento" />
                <x-input type="text" class="w-full" wire:model.defer="name" disabled />
                <x-input-error for="name" />
            </div>

            <div class="mb-3">
                <x-label value="Detalle evento" />
                <x-input type="text" class="w-full" wire:model.defer="detail" disabled />
                <x-input-error for="detail" />
            </div>

            <div class="mb-3">
                <x-label value="Descripción" />
                <x-input type="text" class="w-full" wire:model.defer="description" disabled />
                <x-input-error for="description" />
            </div>


            <div class="d-flex justify-content-between ">
                <div class="mr-2 w-25">
                    <x-label value="Fecha evento" />
                    <x-input type="date" class="w-100" wire:model.defer="date" />
                    <x-input-error for="date" />
                </div>
                <div class="mr-2 w-25">
                    <x-label value="Horario" />
                    <x-input type="time" class="w-100" wire:model.defer="time" />
                    <x-input-error for="time" />
                </div>
                <div class="mr-2 w-25">
                    <x-label value="Cupos totales" />
                    <x-input type="text" class="w-100" wire:model.defer="quota" />
                    <x-input-error for="quota" />
                </div>
                <div class="mr-2 w-25">
                    <x-label value="Estado" />
                    <div class="mt-2">
                        Publicado <input wire:model.defer="state" name="state" type="radio" value="1"
                            class="mr-2" />
                        Borrador <input wire:model.defer="state" name="state" type="radio" value="0" />
                    </div>
                </div>
            </div>

        </x-slot>

        <x-slot name="footer" class="bg-success">
            <x-secondary-button wire:click="$set('open',false)">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click=save>
                Crear evento
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
