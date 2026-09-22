<div x-data="{ sidebarOpen: true }" class="h-[calc(100vh-110px)] min-h-0 bg-white rounded-3xl border overflow-hidden">

    <div class="flex h-full min-h-0">

        {{-- ========================================================= --}}
        {{-- CONVERSATION SIDEBAR --}}
        {{-- ========================================================= --}}

        <div x-show="sidebarOpen" x-transition:enter="transition-all duration-200"
            x-transition:enter-start="w-0 opacity-0" x-transition:enter-end="w-[360px] opacity-100"
            x-transition:leave="transition-all duration-200" x-transition:leave-start="w-[360px] opacity-100"
            x-transition:leave-end="w-0 opacity-0" class="w-[360px] shrink-0 border-r min-h-0 overflow-hidden">

            <livewire:vendor.conversation-list />

        </div>


        {{-- ========================================================= --}}
        {{-- CHAT AREA --}}
        {{-- ========================================================= --}}

        <div class="flex-1 min-w-0 min-h-0 overflow-hidden relative">

            {{-- Sidebar Toggle --}}
            <button type="button" @click="sidebarOpen = !sidebarOpen" class="
                    absolute
                    left-3
                    top-3
                    z-20
                    w-9
                    h-9
                    flex
                    items-center
                    justify-center
                    rounded-xl
                    bg-white
                    border
                    shadow-sm
                    hover:bg-slate-50
                    transition
                " :title="sidebarOpen ? 'Hide conversations' : 'Show conversations'">

                {{-- When sidebar is open --}}
                <svg x-show="sidebarOpen" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>


                {{-- When sidebar is closed --}}
                <svg x-show="!sidebarOpen" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

            </button>


            <livewire:vendor.chat-window />

        </div>

    </div>

</div>