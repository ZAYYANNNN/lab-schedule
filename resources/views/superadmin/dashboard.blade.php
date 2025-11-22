<x-app-layout>
    <h1 class="text-xl font-bold">Dashboard</h1>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="text-red-500">Logout</button>
    </form>
</x-app-layout>
