<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Analytics</h1>
        <p class="text-slate-500 mt-2">Messaging performance overview.</p>
    </div>

    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-sm text-slate-500">Total Messages</div>
            <div class="text-3xl font-bold mt-2">{{ $totalMessages }}</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-sm text-slate-500">Pending</div>
            <div class="text-3xl font-bold mt-2 text-amber-600">{{ $pendingMessages }}</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-sm text-slate-500">Sent</div>
            <div class="text-3xl font-bold mt-2 text-green-600">{{ $sentMessages }}</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="text-sm text-slate-500">Failed</div>
            <div class="text-3xl font-bold mt-2 text-red-600">{{ $failedMessages }}</div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="font-semibold text-lg">Recent Campaigns</h2>
        </div>

        <table class="w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="p-4 text-left">Campaign</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentCampaigns as $campaign)
                    <tr class="border-t">
                        <td class="p-4">{{ $campaign->name }}</td>
                        <td class="p-4">{{ ucfirst($campaign->status) }}</td>
                        <td class="p-4">{{ $campaign->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-slate-400">No campaigns yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
