<div>
    {{--  <x-button wire:click="$set('openReservation', true)">
        Nueva Reservación
    </x-button>
 --}}

    <x-dialog-modal wire:model="openReservation">
        <x-slot name="title">
            Crear nueva reservación <span class="font-medium text-red-600">- Todos los campos son obligatorios</span>
        </x-slot>
        <x-slot name="content">
            @if ($wait == 0 )
            <div class="mb-3">
                <x-label value="Evento" />
                <select wire:model="selectedProgramming" class="form-control" @if($editReservation) disabled @endif >
                  @if ($programmings && count($programmings) > 0)
                  @foreach ($programmings as $programming)
                 
                      <option value="@if($programming->id){{ $programming->id }}@endif">
                          {{ $programming->initial_date. ' - '. $programming->initial_time }}
                      </option>
                  @endforeach
                      
                  @endif
                </select>
            </div>
                
            <div class="mb-3">
                <x-label value="Cupos disponibles" />
                <x-input type="number" class="w-full" wire:model.defer="quotaAvailable" disabled />
            </div>
            @endif
            <div class="mb-3">
                <x-label value="Reserva cupos" />
                <x-input type="number" class="w-full" wire:model.defer="quota" />
                <x-input-error for="quota" />
            </div>

        </x-slot>



        <x-slot name="footer" class="bg-success">
            <x-secondary-button wire:click=openModalCreateUser>
                Atrás
            </x-secondary-button>
            <x-danger-button wire:click=save>
                Continuar
            </x-danger-button>

        </x-slot>
    </x-dialog-modal>
</div>
