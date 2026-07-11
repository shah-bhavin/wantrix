<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">

    <div>
        @php
        $page = \App\Support\Page::current();
        @endphp

        <div>
            <h2 class="font-semibold text-lg">
                {{ data_get($page, 'page.heading', data_get($page, 'label')) }}
            </h2>

            @if(!empty($page['description']))
                {{ data_get($page, 'page.description') }}
            @endif
        </div>
    </div>

    <div class="flex items-center gap-4">

        {{-- Notifications --}}
        <button class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
            🔔
        </button>

        {{-- User --}}
        <div class="flex items-center gap-3">

            <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="hidden md:block">
                <div class="font-medium text-sm text-slate-800">
                    {{ auth()->user()->name }}
                </div>

                <div class="text-xs text-slate-500">
                    {{ auth()->user()->email }}
                </div>
            </div>

        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50">

                <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5" />

                Logout

            </button>
        </form>

    </div>

</header>