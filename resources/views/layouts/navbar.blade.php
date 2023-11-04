<nav x-data="{ open: false }" class="navbar bg-light w-100 pe-3">

    <div class="container-fluid d-flex ">
        <!-- Navigation Links -->
        @php
            $routeName = request()
                ->route()
                ->getName();
            $routeData = [
                'admin.events.users' => ['icon' => 'hUser.svg', 'text' => 'Administrar usuarios'],
                'admin.events.register' => ['icon' => 'hticket.svg', 'text' => 'Registro a eventos'],
                'admin.events.index' => ['icon' => 'hEvent.svg', 'text' => 'Administrar eventos'],
                'admin.events.inform' => ['icon' => 'hReports.svg', 'text' => 'Reportes'],
                'admin.events.readqr' => ['icon' => 'hqr.svg', 'text' => 'Leer QR'],
            ];
        @endphp

        @isset($routeData[$routeName])
            <div class="d-flex align-items-center">
                <img class="img-fluid me-2" src="{{ asset('/assets/icons/' . $routeData[$routeName]['icon']) }}">
                <span class="text-white">
                    {{ $routeData[$routeName]['text'] }}
                </span>
            </div>
        @endisset


        <div class="hidden sm:flex sm:items-center sm:ml-6">
            <!-- Settings Dropdown -->
            <div class="ml-3 relative">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button
                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <img class="h-8 w-8 rounded-full object-cover"
                                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                        @else
                            <span class="d-flex align-items-center">
                                <span class="text-white me-2"> <b>Hola</b>, {{ Auth::user()->name }} </span>
                                <img class="img-fluid  items" src="{{ asset('/assets/icons/icono-usuario.svg') }}">

                            </span>
                        @endif
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                {{ __('API Tokens') }}
                            </x-dropdown-link>
                        @endif

                        <div class="border-t border-gray-200"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
            <button @click="open = ! open"
                class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-red-600 focus:outline-none focus:bg-red-600 focus:text-black transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden burguer w-100">
        <div class="pt-2 pb-3 space-y-1">

            <div class="d-flex flex-column m-4">
                <hr>
                <a href="{{ route('admin.events.users') }}"
                    class="link_side_mobile {{ request()->routeIs('admin.events.users') ? 'active_mobile' : '' }}">
                    <span class="me-2">
                        <img draggable="false" src="{{ asset('/assets/icons/hUser.svg') }}"[alt]="module._nombreMostrar">
                    </span>
                    <span class="fs-12px fw-semibold">
                        Administrar usuarios
                    </span>
                </a>
        
                <hr>
                <a href="{{ route('admin.events.register') }}"
                    class="link_side_mobile {{ request()->routeIs('admin.events.register') ? 'active_mobile' : '' }}">
                    <span class="me-2">
                        <img draggable="false" src="{{ asset('/assets/icons/hticket.svg') }}"[alt]="module._nombreMostrar">
                    </span>
                    <span class="fs-12px fw-semibold">
                        Registro a eventos
                    </span>
                </a>
        
                <hr>
                <a href="{{ route('admin.events.index') }}"
                    class="link_side_mobile {{ request()->routeIs('admin.events.index') ? 'active_mobile' : '' }}">
                    <span class="me-2">
                        <img draggable="false" src="{{ asset('/assets/icons/hEvent.svg') }}"[alt]="module._nombreMostrar">
                    </span>
                    <span class="fs-12px fw-semibold">
                        Administrar eventos
                    </span>
                </a>
        
                <hr>
                <a href="{{ route('admin.events.inform') }}"
                    class="link_side_mobile {{ request()->routeIs('admin.events.inform') ? 'active_mobile' : '' }}">
                    <span class="me-2">
                        <img draggable="false" src="{{ asset('/assets/icons/hReports.svg') }}"[alt]="module._nombreMostrar">
                    </span>
                    <span class="fs-12px fw-semibold">
                        Reportes
                    </span>
                </a>
                <hr>
                <a href="{{ route('admin.events.readqr') }}"
                    class="link_side_mobile {{ request()->routeIs('admin.events.readqr') ? 'active_mobile' : '' }}">
                    <span class="me-2">
                        <img draggable="false" src="{{ asset('/assets/icons/hqr.svg') }}"[alt]="module._nombreMostrar">
                    </span>
                    <span class="fs-12px fw-semibold">
                        Leer Qr
                    </span>
                </a>
            </div>
        </div>






        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                    {{--  <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div> --}}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')" class="burguer">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')" class="burguer">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();"
                        class="burguer">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block py-2 text-xs">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block py-2 text-xs">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
