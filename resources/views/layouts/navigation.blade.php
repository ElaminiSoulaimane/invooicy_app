<nav x-data="{ open: false, langDropdown: false }" x-init="() => { $watch('open', value => { if (value) { document.body.classList.add('overflow-hidden') } else { document.body.classList.remove('overflow-hidden') } }) }" class="bg-perf-black border-b border-gray-100 m-4 rounded-[26px]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" @click.prevent="window.location.href='{{ route('dashboard') }}'">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @foreach (['dashboard', 'clients.index', 'products.index', 'services.index', 'invoices.index', 'payments.index'] as $route)
                        <x-nav-link :href="route($route)" :active="request()->routeIs(Str::before($route, '.') . '.*')"
                            @click.prevent="window.location.href='{{ route($route) }}'">
                            {{ __('navigation.' . Str::before($route, '.')) }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>


            <!-- Centered Language Dropdown -->
            <div class="flex items-center">
                <div class="relative">
                    <button @click="langDropdown = !langDropdown"
                        class="flex items-center p-2    focus:outline-none focus:ring focus:ring-blue-500">
                        <img src="{{ app()->getLocale() === 'fr' ? asset('images/france-flag.png') : asset('images/morocco-flag.png') }}"
                            alt="{{ app()->getLocale() === 'fr' ? 'French Flag' : 'Moroccan Flag' }}" class="h-6 w-8">
                    </button>
                    <div x-show="langDropdown" @click.outside="langDropdown = false"
                        class="absolute right-0 z-10 mt-2 w-20 bg-white border border-gray-200  rounded-[26px] shadow-lg">
                        <a href="{{ route('language.switch', 'fr') }}"
                            class="flex items-center  px-2 py-1 text-gray-700 hover:bg-gray-100">
                            <img src="{{ asset('images/france-flag.png') }}" alt="French Flag"
                                class="h-5 w-7 inline mr-1"> Français
                        </a>
                        <a href="{{ route('language.switch', 'ar') }}"
                            class="flex items-center  px-2 py-1 text-gray-700 hover:bg-gray-100">
                            <img src="{{ asset('images/morocco-flag.png') }}" alt="Moroccan Flag"
                                class="h-5 w-7 inline mr-1"> العربية
                        </a>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="rounded-[26px] inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content" class="rounded-[26px]">
                        <x-dropdown-link :href="route('profile.edit')"
                            @click.prevent="window.location.href='{{ route('profile.edit') }}'">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" x-data
                            @submit.prevent="() => { $el.submit(); }">
                            @csrf
                            <x-dropdown-link :href="route('logout')" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2  rounded-[26px] text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach (['dashboard', 'clients.index', 'products.index', 'services.index', 'invoices.index', 'payments.index'] as $route)
                <x-responsive-nav-link :href="route($route)" :active="request()->routeIs(Str::before($route, '.') . '.*')"
                    @click.prevent="window.location.href='{{ route($route) }}'">
                    {{ __('navigation.' . Str::before($route, '.')) }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    @click.prevent="window.location.href='{{ route('profile.edit') }}'">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data @submit.prevent="() => { $el.submit(); }">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
