<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold">Payments</h1>
            <p class="text-slate-500 mt-2">View all payment transactions.</p>
        </div>
    </div>

    <x-vendor.table>
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b">
                    <th class="text-left p-4">Payment ID</th>
                    <th class="text-left p-4">Amount</th>
                    <th class="text-left p-4">Gateway</th>
                    <th class="text-left p-4">Status</th>
                    <th class="text-left p-4">Paid At</th>
                    <th class="text-left p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="border-b">
                        <td class="p-4">{{ $payment->payment_number ?? $payment->id }}</td>
                        <td class="p-4">₹{{ number_format($payment->amount, 2) }}</td>
                        <td class="p-4">{{ ucfirst($payment->gateway ?? 'Manual') }}</td>
                        <td class="p-4">
                            <x-vendor.status-badge :status="$payment->status->value ?? $payment->status" />
                        </td>
                        <td class="p-4">{{ $payment->paid_at?->format('d M Y H:i') }}</td>
                        <td class="p-4">
                            <button class="text-amber-600 hover:text-amber-700">View</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-slate-500">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-vendor.table>

    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>
