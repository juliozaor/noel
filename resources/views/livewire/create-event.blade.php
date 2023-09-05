<div>
    <x-button wire:click="$set('open',true)">
        Crear evento
    </x-button>


    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Crear nuevo evento
        </x-slot>

        <x-slot name="content">
            <div class="mb-2">
                <x-label value="Nombre del evento" />
                <x-input type="text" class="w-full" wire:model.defer="name" />
            </div>

            <div class="mb-2">
                <x-label value="Detalle evento" />
                <x-input type="text" class="w-full" wire:model.defer="detail" />
            </div>

            <div class="mb-2">
                <x-label value="Descripción" />
                <x-input type="text" class="w-full" wire.model.defer="description" />
            </div>




        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open',false)">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click=save>
                Crear evento
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
