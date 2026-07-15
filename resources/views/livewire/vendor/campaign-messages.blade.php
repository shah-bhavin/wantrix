<div>

    {{-- Header --}}
    {{-- <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-2xl font-bold">
                Campaign Messages
            </h2>

            <p class="text-slate-500">

                {{ $campaign->name }}

            </p>

        </div>

    </div> --}}

    <x-vendor.filter-bar :status="true" :statuses="\App\Enums\MessageStatus::cases()" :total="$this->total" />

    @if(!empty($selected))

    <div class="mb-4 bg-amber-50 border rounded-xl p-4 flex justify-between items-center">

        <div>

            {{ count($selected) }} message(s) selected

        </div>

        <div class="flex gap-3">

            <button wire:click="retrySelected" class="bg-green-600 text-white px-4 py-2 rounded-xl">

                Retry Failed

            </button>

            <button wire:click="deleteSelected" class="bg-red-600 text-white px-4 py-2 rounded-xl">

                Delete

            </button>

        </div>

    </div>

    @endif
    {{-- Table --}}
    <div class="bg-white rounded-2xl border overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-slate-50">

                <tr>
                    <th class="px-6 py-4">

                        <input type="checkbox" wire:model.live="selectAll">

                    </th>

                    <th wire:click="sortBy('contact_id')" class="px-6 py-4 text-left cursor-pointer">
                        <div class="flex items-center gap-2">
                            Contact
                            @if($sortField == 'contact_id')
                            {{ $sortDirection == 'asc' ? '↑' : '↓' }}
                            @endif
                        </div>
                    </th>

                    <th class="px-6 py-4 text-left">Phone</th>

                    <th wire:click="sortBy('status')" class="px-6 py-4 text-left cursor-pointer">
                        <div class="flex items-center gap-2">
                            Status
                            @if($sortField == 'status')
                            {{ $sortDirection == 'asc' ? '↑' : '↓' }}
                            @endif
                        </div>
                    </th>

                    <th wire:click="sortBy('sent_at')" class="px-6 py-4 text-left cursor-pointer">
                        <div class="flex items-center gap-2">
                            Sent At
                            @if($sortField == 'sent_at')
                            {{ $sortDirection == 'asc' ? '↑' : '↓' }}
                            @endif
                        </div>
                    </th>

                    <th class="px-6 py-4 text-right">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($messages as $message)

                <tr class="border-t">
                    <td class="px-6 py-4">

                        <input type="checkbox" value="{{ $message->id }}" wire:model.live="selected">

                    </td>

                    <td class="px-6 py-4">

                        {{ $message->contact->name }}

                    </td>

                    <td class="px-6 py-4">

                        {{ $message->contact->phone_number }}

                    </td>

                    <td class="px-6 py-4">

                        <x-vendor.status-badge :status="$message->status" />

                    </td>

                    <td class="px-6 py-4">

                        {{ $message->sent_at?->format('d M Y h:i A') ?? '-' }}

                    </td>

                    <td class="px-6 py-4 text-right">

                        <button wire:click="$dispatch('show-message',{ id: {{ $message->id }} })"
                            class="text-blue-600 hover:underline">

                            View

                        </button>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="p-10 text-center text-slate-400">

                        No messages found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">

        {{ $messages->links() }}

    </div>

    <livewire:vendor.message-drawer />

</div>