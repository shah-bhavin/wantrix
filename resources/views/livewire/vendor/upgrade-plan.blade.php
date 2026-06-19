<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Upgrade Plan</h1>
        <p class="text-slate-500 mt-2">Choose the best plan for your business.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        @foreach($plans as $plan)
            <x-vendor.plan-card :plan="$plan">
                <button wire:click="selectPlan({{ $plan->id }})" class="w-full py-3 rounded-xl bg-amber-500 text-white">
                    Select Plan
                </button>
            </x-vendor.plan-card>
        @endforeach
    </div>
</div>
