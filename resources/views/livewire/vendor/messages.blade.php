<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-8 text-slate-900">Messages</h1>

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                    <th class="p-4 text-left">Campaign</th>
                    <th class="p-4 text-left">Contact</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($messages as $message)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 font-medium text-slate-900">
                            {{ $message->campaign->name }}
                        </td>
                        <td class="p-4 text-slate-700">
                            {{ $message->contact->name }}
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                {{ ucfirst($message->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-500">
                            {{ $message->created_at->diffForHumans() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
</div>
