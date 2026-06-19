<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Company Settings</h1>
        <p class="mt-2 text-slate-500">Manage your company information, branding and business details.</p>
    </div>

    {{-- Success Message --}}
    @if(session()->has('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">

        {{-- Logo Section --}}
        <div class="p-8 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">Company Branding</h2>

            <div class="flex flex-col md:flex-row items-start gap-6">
                <div>
                    @if($logo && !is_string($logo))
                        {{-- Livewire Temporary Upload Preview --}}
                        <img src="{{ $logo->temporaryUrl() }}" alt="Logo Preview" class="h-28 w-28 rounded-2xl object-cover border border-slate-200">
                    @elseif(auth()->user()->vendor?->logo)
                        {{-- Saved Database Logo --}}
                        <img src="{{ asset('storage/' . auth()->user()->vendor->logo) }}" alt="Company Logo" class="h-28 w-28 rounded-2xl object-cover border border-slate-200">
                    @else
                        {{-- Initials Placeholder --}}
                        <div class="h-28 w-28 rounded-2xl bg-amber-100 flex items-center justify-center text-3xl font-bold text-amber-600">
                            {{ strtoupper(substr($name ?? 'C', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Company Logo</label>
                    <input type="file" wire:model="logo" class="block w-full text-sm border border-slate-300 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500 mt-2">PNG, JPG or WEBP. Maximum 2MB.</p>

                    @error('logo')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Company Information --}}
        <div class="p-8 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">Company Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">Company Name</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    @error('name') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">Email Address</label>
                    <input type="email" wire:model="email" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                    @error('email') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">Phone Number</label>
                    <input type="text" wire:model="phone" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">Website</label>
                    <input type="text" wire:model="website" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>
            </div>
        </div>

        {{-- Address Information --}}
        <div class="p-8 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">Address Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2 text-slate-700">Address</label>
                    <textarea wire:model="address" rows="3" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">City</label>
                    <input type="text" wire:model="city" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">State</label>
                    <input type="text" wire:model="state" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">Country</label>
                    <input type="text" wire:model="country" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">Postal Code</label>
                    <input type="text" wire:model="postal_code" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>
            </div>
        </div>

        {{-- Business Information --}}
        <div class="p-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">Business Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">GST Number</label>
                    <input type="text" wire:model="gst_number" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-700">Timezone</label>
                    <select wire:model="timezone" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        <option value="Asia/Kolkata">Asia/Kolkata</option>
                        <option value="UTC">UTC</option>
                        <option value="America/New_York">America/New_York</option>
                    </select>
                </div>
            </div>
        </div>

    </div>

    {{-- Footer Actions --}}
    <div class="mt-8 flex justify-end">
        <button 
            wire:click="save" 
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-8 py-3 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white font-medium rounded-2xl transition shadow-sm"
        >
            <span wire:loading.remove wire:target="save">
                Save Changes
            </span>
            
            <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            </span>
        </button>
    </div>

</div>
