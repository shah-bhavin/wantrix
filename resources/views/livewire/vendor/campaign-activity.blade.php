<div class="space-y-6">

    @forelse($activities as $activity)

        <div class="flex gap-4">

            {{-- Timeline Line --}}
            <div class="flex flex-col items-center">

                <div class="w-3 h-3 rounded-full bg-blue-600"></div>

                @unless($loop->last)
                    <div class="w-px flex-1 bg-slate-200"></div>
                @endunless

            </div>

            {{-- Activity Content --}}
            <div class="pb-6">

                <div class="font-semibold text-slate-800">

                    {{ $activity->type }}

                </div>

                <div class="text-sm text-slate-600">

                    {{ $activity->description }}

                </div>

                <div class="text-xs text-slate-400 mt-1">

                    {{ $activity->created_at->format('d M Y, h:i A') }}

                </div>

            </div>

        </div>

    @empty

        <div class="text-center py-10 text-slate-400">

            No activity found.

        </div>

    @endforelse

</div>