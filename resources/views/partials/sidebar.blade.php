@php
    $user = auth()->user();
@endphp

<aside class="w-64 h-screen bg-white border-r flex flex-col fixed left-0 top-0 z-50">

    <div class="px-6 py-6 border-b">
        <div class="flex items-center space-x-3">
            <div class="bg-blue-600 text-white p-3 rounded-xl">
                <span class="material-symbols-outlined">lab_panel</span>
            </div>
            <div>
                <h1 class="text-lg font-semibold">Lab Manager</h1>
                <p class="text-sm text-gray-500">
                    {{ $user->role === 'superadmin' ? 'Super Admin' : 'Admin' }}
                </p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Dashboard</span>
        </a>

        {{-- Daftar Lab --}}
        <a href="{{ route('labs.index') }}" class="menu-item {{ request()->routeIs('labs.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">meeting_room</span>
            <span>Daftar Lab</span>
        </a>

        {{-- Daftar Aset Lab --}}
        <a href="{{ route('assets.index') }}" class="menu-item {{ request()->routeIs('assets.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">inventory_2</span>
            <span>Daftar Aset Lab</span>
        </a>

        {{-- Jadwal Lab --}}
        <a href="{{ route('schedules.index') }}"
            class="menu-item {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">calendar_month</span>
            <span>Jadwal Lab</span>
        </a>

        @if($user->role === 'superadmin')
            {{-- Kelola Admin --}}
            <a href="{{ route('users.index') }}" class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">manage_accounts</span>
                <span>Kelola Admin</span>
            </a>
        @endif

        @if($user->role !== 'superadmin')
            {{-- Peminjaman Barang --}}
            <a href="{{ route('borrowings.index') }}"
                class="menu-item {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                <span class="material-symbols-outlined">shopping_bag</span>
                <span>Peminjaman Barang</span>
            </a>
        @endif
    </nav>

    <div class="px-4 py-6 border-t">
        <div class="bg-gray-100 rounded-xl p-4 mb-3">
            <p class="font-semibold">{{ $user->name }}</p>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">
                <span class="material-symbols-outlined">logout</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>

</aside>

<style>
    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 10px;
        color: #4b5563;
    }

    .menu-item:hover {
        background: #f3f4f6
    }

    .menu-item.active {
        background: #e0ebff;
        color: #2563eb
    }

    .logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
    }

    .logout-btn:hover {
        background: #f3f4f6
    }
</style>