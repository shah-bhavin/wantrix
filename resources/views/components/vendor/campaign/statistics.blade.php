<div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-6 mt-8">
    <x-vendor.stat-card title="Total" :value="$stats['total']" icon="chat-bubble-left-right" color="slate" />
    <x-vendor.stat-card title="Pending" :value="$stats['pending']" icon="clock" color="amber" />
    <x-vendor.stat-card title="Queued" :value="$stats['queued']" icon="queue-list" color="yellow" />
    <x-vendor.stat-card title="Sending" :value="$stats['sending']" icon="paper-airplane" color="blue" />
    <x-vendor.stat-card title="Delivered" :value="$stats['delivered']" icon="check-badge" color="emerald" />
    <x-vendor.stat-card title="Read" :value="$stats['read']" icon="eye" color="purple" />
    <x-vendor.stat-card title="Sent" :value="$stats['sent']" icon="check" color="green" />
    <x-vendor.stat-card title="Failed" :value="$stats['failed']" icon="x-circle" color="red" />
</div>