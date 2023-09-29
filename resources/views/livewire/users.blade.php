<div>
    <div class="d-flex flex-column flex-gap-30">
        <div class="row">
            <div class="col-12">
                <div class="card w-100">
                    <div class="card-header fw-bold fs-14px mt-2">
                        {{ $title }}
                    </div>
                    <div class="card-body">
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
                                        <th scope="col" class="cursor-pointer"
                                            wire:click="order('profiles.document')">
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

                                        <th colspan="2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users && count($users) > 0)
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->document }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->cell }}</td>
                                                <td>

                                                    <button wire:click="editRegisterUser({{ $user->document }})"><img
                                                            src="{{ asset('/assets/icons/edit.svg') }}" alt="editar"
                                                            style="max-width: 15px" /></button>
                                                </td>
                                                <td>

                                                    <button wire:click="deleteUser({{ $user->id }})"><img
                                                            src="{{ asset('/assets/icons/trash.svg') }}" alt="editar"
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
                    @if ($users && $users->hasPages())

                        <div class="card-footer d-flex justify-content-end " wire:key="list-users">
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
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="sweetalert2.all.min.js"></script>
        <script>
            Livewire.on('delUser', userId => {
                Swal.fire({
                    title: 'Se eliminará el usuario',
                    text: "¿Desea continuar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('users','confirmDelete',userId)
                        Swal.fire(
                            'Eliminado!',
                            'El usuario se ha eliminado.',
                            'success'
                        )
                    }
                });

            });
        </script>
        {{-- <script>
            Livewire.on('consoleLog', ({ pagina }) => {
                console.log(pagina);
            });
        </script> --}}
    @endpush
</div>
