@php
    $user = auth()->user();
@endphp

<aside class="w-72 h-screen bg-slate-900 flex flex-col fixed left-0 top-0 z-50 shadow-2xl overflow-hidden">
    {{-- Decorative Background Glow --}}
    <div class="absolute -top-24 -left-24 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl"></div>
    
    <div class="px-8 py-10 relative">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 shadow-lg shadow-blue-500/30 text-white rounded-2xl flex items-center justify-center animate-pulse-slow">
                <span class="material-symbols-outlined text-2xl">science</span>
            </div>
            <div>
                <h1 class="text-xl font-black text-white tracking-tight">SmartLab</h1>
                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest opacity-80">
                    {{ $user->role === 'superadmin' ? 'Super Admin' : 'Admin Area' }}
                </p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-2 overflow-y-auto scrollbar-none py-4">
        <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Main Menu</p>
        
        {{-- Jadwal Lab --}}
        <a href="{{ route('schedules.index') }}"
            class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('schedules*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
            <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110">calendar_month</span>
            <span class="text-sm font-bold tracking-tight">Jadwal Lab</span>
        </a>

        {{-- Daftar Lab --}}
        <a href="{{ route('labs.index') }}" 
            class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('labs.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
            <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110">meeting_room</span>
            <span class="text-sm font-bold tracking-tight">Daftar Lab</span>
        </a>

        {{-- Daftar Aset Lab --}}
        <a href="{{ route('assets.index') }}" 
            class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('assets.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
            <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110">inventory_2</span>
            <span class="text-sm font-bold tracking-tight">Daftar Aset Lab</span>
        </a>

        {{-- Peminjaman Barang --}}
        <a href="{{ route('borrowings.index') }}"
            class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('borrowings.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
            <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110">shopping_bag</span>
            <span class="text-sm font-bold tracking-tight">Peminjaman Barang</span>
        </a>

        @if($user->role === 'superadmin')
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Administration</p>
                {{-- Kelola Admin --}}
                <a href="{{ route('users.index') }}" 
                    class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110">manage_accounts</span>
                    <span class="text-sm font-bold tracking-tight">Kelola Admin</span>
                </a>
            </div>
        @endif
    </nav>

    <div class="p-4 border-t border-slate-800 bg-slate-900/50">
        <div class="bg-slate-800/50 rounded-2xl p-4 mb-4 flex items-center gap-3 border border-slate-700/50">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shrink-0 font-black text-xs">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ $user->name }}</p>
                <p class="text-[10px] font-medium text-slate-500 truncate">{{ $user->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="group w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-slate-800 text-slate-400 font-bold text-xs uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                <span>Keluar Aplikasi</span>
            </button>
        </form>
    </div>
</aside>

<style>
    @keyframes pulse-slow {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.9; }
    }
    .animate-pulse-slow {
        animation: pulse-slow 3s ease-in-out infinite;
    }
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
