<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold">
                Media Library
            </h1>

            <p class="text-slate-500 mt-1">
                Upload reusable WhatsApp media.
            </p>
        </div>

    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="rounded-xl bg-green-100 text-green-700 px-4 py-3">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="rounded-xl bg-red-100 text-red-700 px-4 py-3">
        {{ session('error') }}
    </div>
    @endif

    {{-- Upload Card --}}
    <div class="bg-white rounded-2xl border p-6">

        <div class="grid md:grid-cols-3 gap-4">

            <input type="file" wire:model="file" class="border rounded-xl p-3 w-full">

            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search media..."
                class="border rounded-xl p-3 w-full">
            <button wire:click="uploadMedia"
                class="bg-amber-500 hover:bg-amber-600 text-white rounded-xl px-6">
                Upload
            </button>

        </div>
        <div wire:loading wire:target="upload">

            <p class="text-amber-600 mt-3">

                Uploading...

            </p>

        </div>

        @error('file')

        <p class="text-red-600 mt-3">
            {{ $message }}
        </p>

        @enderror

    </div>

    {{-- Media Grid --}}
    <div class="grid md:grid-cols-4 gap-6">

        @forelse($media as $item)

        <div class="bg-white rounded-2xl border overflow-hidden shadow-sm">

            {{-- Preview --}}
            @if($item->type->value === 'image')

            <img src="{{ $item->url }}" class="h-48 w-full object-cover">

            @else

            <div class="h-48 flex items-center justify-center bg-slate-100">

                <span class="text-5xl">

                    📄

                </span>

            </div>

            @endif

            {{-- Details --}}
            <div class="p-4">

                <h3 class="font-semibold truncate">

                    {{ $item->original_name }}

                </h3>

                <p class="text-sm text-slate-500">

                    {{ strtoupper($item->type->value) }}

                </p>

                <p class="text-xs text-slate-400">

                    {{ number_format($item->size / 1024,1) }} KB

                </p>

                <div class="mt-3">

                    @if($item->uploaded_to_meta)

                    <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">

                        Uploaded

                    </span>

                    @else

                    <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">

                        Pending

                    </span>

                    @endif

                </div>

                <button wire:click="delete({{ $item->id }})" class="mt-4 text-red-600 text-sm">

                    Delete

                </button>

            </div>

        </div>

        @empty

        <div class="col-span-4">

            <div class="bg-white rounded-2xl border p-12 text-center">

                <h3 class="text-xl font-semibold">

                    No media uploaded

                </h3>

                <p class="text-slate-500 mt-2">

                    Upload your first media.

                </p>

            </div>

        </div>

        @endforelse

    </div>

</div>