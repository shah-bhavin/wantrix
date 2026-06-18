<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">
    <div>
        <h2 class="font-semibold text-lg">Dashboard</h2>
    </div>

    <div class="flex items-center gap-4">
        <button class="w-10 h-10 rounded-full bg-slate-100">
            🔔
        </button>

        <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>
</header>
