<div>
    <div class="d-flex flex-column flex-gap-30">
        <div class="row">
            <div class="col-12">
                <div class="card w-100">
                    <div class="card-header fw-bold fs-14px mt-2">
                        {{ $title }}
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-9 mt-2">
                                 <div class="row">
                                     <div class="col-md-4 mt-2 d-flex align-items-center">
                                        <x-label value="Buscar desde " class="mr-2" />
                                    <x-input type="date" class="w-100" wire:model="begin" />
                                    <x-input-error for="begin" />
                                     </div>
                                     <div class="col-md-3 mt-2 d-flex align-items-center">
                                        <x-label value="Hasta " class="mr-2" />
                                        <x-input type="date" class="w-100" wire:model="end" />
                                        <x-input-error for="end" />
                                     </div>
                                     <div class="col-md-3 mt-2 d-flex align-items-center">
                                         <x-label value="Ver " class="mr-2"/>
                                        
                                         <select wire:model="all" class="form-control">
                                            <option value="0">Todos</option>
                                            <option value="1">Cupos disponibles</option>
                                            <option value="2">Sin cupo</option>
    
                                        </select>
                                     </div>
     
                                     <div class="col-md-2 mt-2 d-flex align-items-center">
                                        <button class="totales" disabled>
                                            <span class="fw-semibold fs-14px">Total:</span>
                                            <span
                                                class="fs-12px fw-semibold"style="margin-left: 5px">{{ $count }}</span>
                                        </button>
                                     </div>
                                 </div>
                            </div>
                            <div class="col-md-3 mt-2 d-flex align-items-center">
                                <span class="fs-12px d-flex align-items-center mx-2" id="registrosTotales">Registros por
                                    página</span>
                                <span class="texto-gris fs-12px fw-bold align-items-center">
                                    <select wire:model="cant" class="form-select sm" name="" id="">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                    </select>
                                </span>
                            </div>
                         </div>






                        <div class="container-table d-flex table-responsive">
                            <table class="table table-striped fs-12px">
                                <thead>
                                    <tr>
                                        <th scope="col" class="cursor-pointer" wire:click="order('id')">
                                            ID evento
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>
                                        <th scope="col" class="cursor-pointer" wire:click="order('initial_date')">
                                            Fecha evento
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>
                                        <th scope="col" class="cursor-pointer" wire:click="order('initial_date')">
                                            Hora evento
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>
                                        <th scope="col" class="cursor-pointer" wire:click="order('quota_available')">
                                            Cupos totales
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>
                                        <th scope="col" class="cursor-pointer" wire:click="order('quota')">
                                            Cupos disponibles
                                            <i class="fas fa-sort ml-1 mt-1"></i>
                                        </th>
                                        <th colspan="2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($programmings->count())
                                        @foreach ($programmings as $programming)
                                            <tr>


                                                <td>{{ $programming->id }}</td>
                                                <td>{{ $programming->initial_date }}</td>

                                                <td>{{ \Carbon\Carbon::parse($programming->initial_time)->format('h:i A') }}
                                                </td>
                                                <td>{{ $programming->quota }}</td>
                                                <td>{{ $programming->quota_available }}</td>
                                                <td>
                                                    {{--  @livewire('edit-programming', ['programming' => $programming], key($programming->id)) --}}
                                                    <button wire:click="registerUserInEvent({{ $programming }},'home')"><img
                                                            src="{{ asset('/assets/icons/usermas.svg') }}"
                                                            alt="editar" style="max-width: 15px" /></button>
                                                </td>
                                                <td>
                                                    {{--  @livewire('edit-programming', ['programming' => $programming], key($programming->id)) --}}
                                                    <button wire:click="registerUserInEvent({{ $programming }},'profile')"><img
                                                            src="{{ asset('/assets/icons/wait.svg') }}" alt="editar"
                                                            style="max-width: 15px" /></button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <td colspan="6">
                                            <label class="d-flex justify-content-center fs-14px">
                                                No se encontraron datos
                                            </label>
                                        </td>
                                    @endif



                                </tbody>

                            </table>
                        </div>
                    </div>
                    @if ($programmings && $programmings->hasPages())
                        <div class="card-footer d-flex justify-content-end " wire:key="register-users">
                            {{ $programmings->links() }}

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

   
</div>
