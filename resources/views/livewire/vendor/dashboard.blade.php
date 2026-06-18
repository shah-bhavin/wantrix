<div>
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-slate-500 mt-2">Here's what's happening with your account today.</p>
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <x-vendor.stats-card title="Current Plan" :value="$stats['plan_name']" />

        <x-vendor.stats-card title="Users" :value="$stats['current_users'].' / '.$stats['max_users']" :percentage="$stats['max_users'] ? ($stats['current_users'] / $stats['max_users']) * 100 : 0" />

        <x-vendor.stats-card title="Contacts" :value="$stats['contacts_count'].' / '.$stats['max_contacts']" />
        
        <x-vendor.stats-card title="Subscription" :value="$stats['subscription_days_left'] . ' Days Left'" />
    </div>
</div>
