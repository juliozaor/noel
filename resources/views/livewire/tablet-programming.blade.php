<div>
    <div class="d-flex flex-column flex-gap-30">
        <div class="row">
            <div class="col-12">
                <div class="card w-100">
                    <div class="card-header fw-bold fs-14px mt-2">
                        {{ $title }}
                    </div>
                    <div class="card-body">
                        <div class="contenedor">
                            <div class="barraBusqueda">
                                <input type="text" class="form-control" placeholder="Buscar..." wire:model="search">
                            </div>
                            <div>
                                <button class="totales" disabled>
                                    <span class="fw-semibold fs-14px">Total:</span>
                                    <span class="fs-12px fw-semibold"style="margin-left: 5px">{{ $count }}</span>
                                </button>
                            </div>
                            <div class="d-flex justify-self-end flex-gap-5 ms-auto mb-3">
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
                                                
                                                <td>{{ \Carbon\Carbon::parse($programming->initial_time)
                                                ->format('h:i A') }}</td>
                                                <td>{{ $programming->quota }}</td>
                                                <td>{{ $programming->quota_available }}</td>
                                                <td>
                                                    {{--  @livewire('edit-programming', ['programming' => $programming], key($programming->id)) --}}
                                                    <button wire:click="editProgramming({{ $programming }})"><img
                                                            src="{{ asset('/assets/icons/edit.svg') }}" alt="editar"
                                                            style="max-width: 15px" /></button>
                                                </td>
                                                <td>

                                                    <label class="switch">
                                                        <input type="checkbox" data-id="{{ $programming->id }}"
                                                            class="togger_checkbox" data-onstyle="success"
                                                            data-offstyle="danger" data-toggle="toggle" data-on="Active"
                                                            data-off="InActive"
                                                            {{ $programming->state ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>

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
                    @if ($programmings->hasPages())
                        <div class="card-footer d-flex justify-content-end ">
                            {{ $programmings->links() }}

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model="openEditProgramming">
        <x-slot name="title">
            Editar evento
        </x-slot>

        <x-slot name="content">
            <div class="mb-3">
                <x-label value="Nombre del evento" />
                <x-input type="text" class="w-full" wire:model.defer="name" disabled />
                <x-input-error for="name" />
            </div>

           {{--  <div class="mb-3">
                <x-label value="Detalle evento" />
                <x-input type="text" class="w-full" wire:model.defer="detail" disabled />
                <x-input-error for="detail" />
            </div>

            <div class="mb-3">
                <x-label value="Descripción" />
                <x-input type="text" class="w-full" wire:model.defer="description" disabled />
                <x-input-error for="description" />
            </div> --}}


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
                            class="mr-2" checked>
                        Borrador <input wire:model.defer="state" name="state" type="radio" value="0" />
                    </div>
                </div>
            </div>

        </x-slot>

        <x-slot name="footer" class="bg-success">
            <x-secondary-button wire:click="$set('openEditProgramming',false)">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click=update>
                Guardar evento
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

</div>


<script>
    $('.togger_checkbox').change(function() {
        var state = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        console.log(id);
        $.ajax({
            type: "GET",
            datatype: "json",
            url: "{{ route('api.programmings.updateState') }}",
            data: {
                'state': state,
                'id': id
            },
            success: function(data) {
                //$('#resp' + id).html(data.var);
                console.log(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error");
            }
        })

    });
</script>
