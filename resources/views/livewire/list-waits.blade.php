<div>
    <div class="contenedor pt-2 px-4 pb-4">
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
    <div class="container-table d-flex table-responsive pt-2 px-4">
        <table class="table table-striped fs-12px">
            <thead>
                <tr>
                    <th scope="col" class="cursor-pointer" wire:click="order('reservations.reservation_date')">
                        Fecha solicitud
                        <i class="fas fa-sort ml-1 mt-1"></i>
                    </th>
                    <th scope="col" class="cursor-pointer" wire:click="order('profiles.document')">
                        Identificación
                        <i class="fas fa-sort ml-1 mt-1"></i>
                    </th>
                    <th scope="col" class="cursor-pointer" wire:click="order('users.name')">
                        Usuario
                        <i class="fas fa-sort ml-1 mt-1"></i>
                    </th>
                    <th scope="col" class="cursor-pointer" wire:click="order('profiles.cell')">
                        Telefono
                        <i class="fas fa-sort ml-1 mt-1"></i>
                    </th>
                    <th scope="col" class="cursor-pointer" wire:click="order('reservations.quota')">
                        Cupos solicitados
                        <i class="fas fa-sort ml-1 mt-1"></i>
                    </th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($users && count($users) > 0)
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->reservation_date }}</td>
                            <td>{{ $user->document }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->cell }}</td>
                            <td>{{ $user->quota }}</td>
                            <td>

                                <div class="form-check mr-2 w-25 d-flex align-items-center">
                                    <input class="form-check-input mr-2" type="checkbox"
                                        wire:model="addUser.{{ $user->id }}" wire:change="updateUserQuotas">
                                </div>
                            </td>
                            <td>

                                <button wire:click="editRegisterWait({{ $user->document }},{{ $user->id }} )"><img
                                        src="{{ asset('/assets/icons/edit.svg') }}" alt="editar"
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
    @if ($users && $users->hasPages())

        <div class="card-footer d-flex justify-content-end " wire:key="list-wait">
            <ul class="pagination">
                {{-- Botón "Anterior" --}}
                @if ($users->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Anterior</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="#" wire:click="previousPage">Anterior</a>
                    </li>
                @endif

                {{-- Enlaces de páginas --}}
                @for ($page = 1; $page <= $users->lastPage(); $page++)
                    @if ($page == $users->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="#"
                                wire:click="gotoPage({{ $page }})">{{ $page }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Botón "Siguiente" --}}
                @if ($users->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="#" wire:click="nextPage">Siguiente</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Siguiente</span>
                    </li>
                @endif
            </ul>
        </div>
    @endif
    <div class="d-flex justify-content-between">
        <div>

            <x-secondary-button>
                Cancelar
            </x-secondary-button>
        </div>
        <div class="d-flex justify-content-between">
            <span>

                Seleccionados {{ $totalQuota }} / {{ $quotaAvailable }}
            </span>
            <x-danger-button wire:click=registerQuota>
                Registrar cupos selecionados
            </x-danger-button>
        </div>
    </div>

</div>
