@php
    use Illuminate\Support\Facades\Gate;

    $menus = [];

    if (auth()->check()) {
        $menus[] = [
            'label' => 'Dashboard',
            'route' => 'redirect',
            'active' => request()->routeIs('dashboard', 'redirect'),
        ];

        // GURU → Coaching
        if (Gate::allows('viewAny', App\Models\Coaching::class)) {
            $menus[] = [
                'label' => 'Coaching',
                'route' => 'coachings.index',
                'active' => request()->routeIs('coachings.*'),
            ];
        }

        // MURID → Jurnal Saya
        if (Gate::allows('view-murid-journal')) {
            $menus[] = [
                'label' => 'Jurnal Saya',
                'route' => 'journals.murid',
                'active' => request()->routeIs('journals.murid'),
            ];
        }

        // ORANG TUA → Jurnal Anak
        if (Gate::allows('view-parent-journal')) {
            $menus[] = [
                'label' => 'Jurnal Anak',
                'route' => 'journals.parent',
                'active' => request()->routeIs('journals.parent'),
            ];
        }

        // ADMIN
        if (Gate::allows('access-admin-panel')) {
            $menus[] = [
                'label' => 'Admin Panel',
                'route' => 'admin.dashboard',
                'active' => request()->routeIs('admin.*'),
            ];
        }
    }
@endphp


<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('landing') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- DESKTOP MENU -->
                @auth
                    <div class="hidden sm:flex sm:space-x-8 sm:ms-10">
                        @foreach ($menus as $menu)
                            @if (Route::has($menu['route']))
                                <x-nav-link :href="route($menu['route'])" :active="$menu['active']">
                                    {{ $menu['label'] }}
                                </x-nav-link>
                            @endif
                        @endforeach
                    </div>
                @endauth
            </div>

            <!-- RIGHT -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 bg-white hover:text-gray-800">
                                <div>{{ auth()->user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-blue-600">
                            Register
                        </a>
                    </div>
                @endguest
            </div>

            <!-- HAMBURGER -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

        @auth
            <div class="pt-2 pb-3 space-y-1">
                @foreach ($menus as $menu)
                    @if (Route::has($menu['route']))
                        <x-responsive-nav-link :href="route($menu['route'])" :active="$menu['active']">
                            {{ $menu['label'] }}
                        </x-responsive-nav-link>
                    @endif
                @endforeach
            </div>
        @endauth


        @guest
            <div class="pt-4 pb-4 space-y-2 text-center">
                <a href="{{ route('login') }}" class="block text-gray-700">Login</a>
                <a href="{{ route('register') }}" class="block font-semibold text-blue-600">Register</a>
            </div>
        @endguest
    </div>
</nav>
