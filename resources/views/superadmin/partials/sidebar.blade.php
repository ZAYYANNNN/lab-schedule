<aside class="w-64 h-screen bg-white border-r flex flex-col">

    <!-- HEADER LOGO + ROLE -->
    <div class="px-6 py-6 border-b">
        <div class="flex items-center space-x-3">
            <div class="bg-blue-600 text-white p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.66 6.479L12 20l-6.82-2.943a12.083 12.083 0 01.66-6.479L12 14z" />
                </svg>
            </div>

            <div>
                <h1 class="text-lg font-semibold">Lab Manager</h1>
                <p class="text-sm text-gray-500">Super Admin</p>
            </div>
        </div>
    </div>

    <!-- MENU -->
    <nav class="flex-1 px-4 py-6 space-y-1">

        <!-- Dashboard -->
        <a href="{{ route('superadmin.dashboard') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 
                {{ request()->routeIs('superadmin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Jadwal Lab -->
        <a href="{{ route('superadmin.jadwal.index') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 
                {{ request()->routeIs('superadmin.jadwal.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3M5 11h14M5 19h14M5 11v8m14-8v8" />
            </svg>
            <span>Jadwal Lab</span>
        </a>
        <!-- Daftar Lab -->
        <a href="{{ route('superadmin.labs.index') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 
                {{ request()->routeIs('superadmin.labs.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                      d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span>Daftar Lab</span>
        </a>

        <!-- Daftar Aset -->
        <a href="{{ route('superadmin.assets.index') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 
                {{ request()->routeIs('superadmin.assets.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                      d="M12 6v12m6-6H6" />
            </svg>
            <span>Daftar Aset Lab</span>
        </a>

        <!-- Pendaftaran Lab -->
        <a href="{{ route('superadmin.pendaftaran.index') }}"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 
                {{ request()->routeIs('superadmin.pendaftaran.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                      d="M12 6v12m6-6H6" />
            </svg>
            <span>Pendaftaran Lab</span>
        </a>

    </nav>


    <!-- USER BOX + LOGOUT -->
    <div class="px-4 py-6 border-t">
        <div class="bg-gray-100 rounded-xl p-4 mb-4">
            <p class="font-semibold">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                class="w-full flex items-center justify-center space-x-2 px-4 py-3 border rounded-lg text-gray-700 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 12H3m12 0l-4-4m4 4l-4 4" />
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>

</aside>
