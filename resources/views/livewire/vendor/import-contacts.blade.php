<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Import Contacts</h1>
        <p class="text-slate-500 mt-2">Upload a CSV file and preview contacts before importing.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8">
        <input type="file" wire:model="csv" accept=".csv" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-colors cursor-pointer">

        @error('csv')
            <div class="text-sm text-red-500 mt-2">
                {{ $message }}
            </div>
        @enderror
    </div>

    @if($importedCount || $skippedCount)
    <div class="mt-6 bg-green-50 border border-green-200 rounded-2xl p-4 text-sm flex gap-6">
        <div class="font-medium text-green-700">
            Imported: {{ $importedCount }}
        </div>
        <div class="text-green-600">
            Skipped: {{ $skippedCount }}
        </div>
    </div>
    @endif


    @if(count($previewRows))
    <div class="mt-8 bg-white rounded-3xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-semibold text-slate-900">Preview</h2>
        </div>

        <table class="w-full text-sm">
            <tbody class="divide-y divide-slate-100">
                @foreach($previewRows as $row)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        @foreach($row as $column)
                            <td class="p-4 text-slate-700">
                                {{ $column }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6 flex justify-end p-6">
            <button wire:click="importContacts" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl transition-colors">
                Import Contacts
            </button>
        </div>
    </div>
    @endif

</div>
