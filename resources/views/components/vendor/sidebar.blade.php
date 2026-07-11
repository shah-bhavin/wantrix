@php
$isActive = request()->routeIs(
'vendor.dashboard'
);
@endphp
<aside class="hidden lg:flex w-64 bg-white border-r border-slate-200 flex-col">
    <div class="h-16 flex items-center px-6 border-b">
        <h1 class="font-bold text-xl">WANTRIX</h1>
    </div>

    <nav class="flex-1 p-4 space-y-2">
    @foreach(config('navigation') as $item)
        {{-- Normal Menu --}}
        @if(isset($item['route']))
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs($item['route']) ? 'bg-amber-50 text-amber-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endif

        {{-- Group Menu --}}
        @if(isset($item['children']))
            <div class="mt-4">
                <div class="px-4 py-2 text-xs font-bold uppercase text-slate-400">
                    {{ $item['label'] }}
                </div>
                @foreach($item['children'] as $child)
                    <a href="{{ route($child['route']) }}" class="flex items-center gap-3 ml-2 px-4 py-2 rounded-lg transition {{ request()->routeIs($child['route']) ? 'bg-amber-50 text-amber-600 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        <x-dynamic-component :component="'heroicon-o-' . $child['icon']" class="w-4 h-4" />
                        <span>{{ $child['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach
</nav>

</aside>