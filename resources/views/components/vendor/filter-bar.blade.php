@props([
    'search' => true,
    'status' => false,
    'statuses' => [],
    'perPage' => true,
    'total' => null,
])

<div class="bg-white rounded-2xl border border-slate-200 p-5 mb-6">

    <div class="flex flex-col lg:flex-row lg:items-center gap-4">

        @if($search)

            <div class="flex-1">

                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search..."

                    class="w-full rounded-xl border-slate-300">

            </div>

        @endif

        @if($status)

            <div class="w-full lg:w-56">

                <select
                    wire:model.live="status"
                    class="w-full rounded-xl border-slate-300">

                    <option value="">
                        All Status
                    </option>

                    @foreach($statuses as $item)

                        <option value="{{ $item->value }}">

                            {{ $item->label() }}

                        </option>

                    @endforeach

                </select>

            </div>

        @endif

        @if($perPage)

            <div class="w-full lg:w-36">

                <select
                    wire:model.live="perPage"
                    class="w-full rounded-xl border-slate-300">
                    <option value="2">2</option>
                    
                    <option value="10">10</option>

                    <option value="25">25</option>

                    <option value="50">50</option>

                    <option value="100">100</option>

                </select>

            </div>

        @endif

        <button
            wire:click="resetFilters"
            class="px-4 py-2 rounded-xl border hover:bg-slate-50">

            Reset

        </button>

        @if($total !== null)

            <div class="ml-auto text-sm text-slate-500">

                Total :

                <span class="font-semibold text-slate-800">

                    {{ number_format($total) }}

                </span>

            </div>

        @endif


    </div>

</div>