<div class="grid md:grid-cols-3 gap-6 mt-8">
            <div class="bg-slate-50 rounded-xl p-5">
                <div class="text-xs uppercase tracking-wide text-slate-500">Created</div>
                <div class="font-semibold mt-2">{{ $campaign->created_at->format('d M Y') }}</div>
                <div class="text-sm text-slate-500">{{ $campaign->created_at->format('h:i A') }}</div>
            </div>

            <div class="bg-slate-50 rounded-xl p-5">
                <div class="text-xs uppercase tracking-wide text-slate-500">Started</div>
                @if($campaign->started_at)
                    <div class="font-semibold mt-2">{{ $campaign->started_at->format('d M Y') }}</div>
                    <div class="text-sm text-slate-500">{{ $campaign->started_at->format('h:i A') }}</div>
                @else
                    <span class="text-slate-400">Not Started</span>
                @endif
            </div>

            <div class="bg-slate-50 rounded-xl p-5">
                <div class="text-xs uppercase tracking-wide text-slate-500">Completed</div>
                @if($campaign->completed_at)
                    <div class="font-semibold mt-2">{{ $campaign->completed_at->format('d M Y') }}</div>
                    <div class="text-sm text-slate-500">{{ $campaign->completed_at->format('h:i A') }}</div>
                @else
                    <span class="text-slate-400">Running...</span>
                @endif
            </div>
        </div>