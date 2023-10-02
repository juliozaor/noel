<div>
     <x-button wire:click="$set('openBulk', true)">
        Carga masiva
    </x-button>


    <x-dialog-modal wire:model="openBulk">
        <x-slot name="title">
            {{ $title }} <span class="font-medium text-red-600">- Todos los campos son obligatorios</span>
        </x-slot>
        <x-slot name="content">
            
            <x-label value="Cargar archivo" />
            <x-input type="file" class="w-full" wire:model="file" />
            <x-input-error for="file" />
        
            @if ($file)
                <div wire:loading wire:target="file">Cargando...</div>
            @endif

        </x-slot>



        <x-slot name="footer" class="bg-success">
            <x-secondary-button wire:click=close>
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click=uploadFile>
                Continuar
            </x-danger-button>

        </x-slot>
    </x-dialog-modal>
</div>
