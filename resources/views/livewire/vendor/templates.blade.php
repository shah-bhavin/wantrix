<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-3xl font-bold">
                WhatsApp Templates
            </h1>

            <p class="text-slate-500 mt-2">
                Sync and manage Meta approved templates.
            </p>

        </div>

        <select wire:model="selectedAccount" class="form-select">
            @foreach($accounts as $account)

            <option value="{{ $account->id }}">

                {{ $account->name }}

                ({{ $account->display_phone_number }})

            </option>

            @endforeach
        </select>

        <button wire:click="syncTemplates"
            class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-medium">

            Sync Templates

        </button>
        

    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-10 text-center">

        <h2 class="text-xl font-semibold mb-3">

            No templates synced

        </h2>

        <p class="text-slate-500">

            Click "Sync Templates" to import your approved Meta templates.

        </p>

    </div>

</div>