<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Subscription History</h1>
        <p class="mt-2 text-slate-500">View all subscription changes.</p>
    </div>

    <div class="space-y-8">
        @forelse($changes as $change)
            <x-vendor.timeline-item :change="$change" />
        @empty
            <div class="bg-white border rounded-2xl p-12 text-center">
                <h3 class="text-lg font-semibold">No Subscription History</h3>
                <p class="mt-2 text-slate-500">Your subscription changes will appear here.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $changes->links() }}
    </div>
</div>
