{{-- Activity Timeline --}}
<div class="mt-12">
    <h2 class="text-xl font-bold text-slate-900 mb-6">Campaign Activity</h2>

    <div class="space-y-6">
        @foreach($timeline as $activity)
        <div class="flex gap-5">
            {{-- Timeline Icon --}}
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center @class([
                                'bg-blue-100 text-blue-600' => $activity['color'] == 'blue',
                                'bg-amber-100 text-amber-600' => $activity['color'] == 'amber',
                                'bg-green-100 text-green-600' => $activity['color'] == 'green',
                                'bg-emerald-100 text-emerald-600' => $activity['color'] == 'emerald',
                            ])">
                    <x-dynamic-component :component="'heroicon-o-'.$activity['icon']" class="w-5 h-5" />
                </div>

                @unless($loop->last)
                <div class="w-px flex-1 bg-slate-200 mt-2"></div>
                @endunless
            </div>

            {{-- Timeline Content --}}
            <div class="pb-8">
                <div class="font-semibold text-slate-900">{{ $activity['title'] }}</div>
                <div class="text-slate-500 mt-1">{{ $activity['description'] }}</div>
                <div class="text-sm text-slate-400 mt-2">
                    {{ $activity['time']->format('d M Y') }} • {{ $activity['time']->format('h:i A') }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>