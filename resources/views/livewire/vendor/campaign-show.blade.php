<div class="max-w-6xl mx-auto" @if($this->shouldPoll()) wire:poll.2s="refreshCampaign" @endif>

    <div wire:loading.flex wire:target="generateMessages,sendCampaign,pauseCampaign,resumeCampaign,cancelCampaign"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm items-center justify-center z-50">
        <div class="bg-white rounded-2xl px-8 py-6 shadow-xl">
            <svg class="animate-spin h-8 w-8 mx-auto text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <p class="mt-4 text-slate-700">
                Processing campaign...
            </p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8">
        <x-vendor.campaign.header :campaign="$campaign" />

        <x-vendor.campaign.actions :campaign="$campaign" />

        <div class="border-b mt-8 mb-8">

            <nav class="flex gap-8">

                <button wire:click="changeTab('overview')" @class([ 'pb-4 font-semibold border-b-2 transition'
                    , 'border-amber-500 text-amber-600'=> $tab === 'overview',
                    'border-transparent text-slate-500 hover:text-slate-800' => $tab !== 'overview',
                    ])>

                    Overview

                </button>

                <button wire:click="changeTab('messages')" @class([ 'pb-4 font-semibold border-b-2 transition'
                    , 'border-amber-500 text-amber-600'=> $tab === 'messages',
                    'border-transparent text-slate-500 hover:text-slate-800' => $tab !== 'messages',
                    ])>

                    Messages

                </button>

                <button wire:click="changeTab('analytics')" @class([ 'pb-4 font-semibold border-b-2 transition'
                    , 'border-amber-500 text-amber-600'=> $tab === 'analytics',
                    'border-transparent text-slate-500 hover:text-slate-800' => $tab !== 'analytics',
                    ])>

                    Analytics

                </button>

                <button wire:click="changeTab('activity')" @class([ 'pb-4 font-semibold border-b-2 transition'
                    , 'border-amber-500 text-amber-600'=> $tab === 'activity',
                    'border-transparent text-slate-500 hover:text-slate-800' => $tab !== 'activity',
                    ])>

                    Activity

                </button>

            </nav>

        </div>


        @if($tab === 'overview')

        <livewire:vendor.campaign-overview :campaign="$campaign" :key="'overview-'.$campaign->id" />

        @endif


        @if($tab === 'messages')

        <livewire:vendor.campaign-messages :campaign="$campaign" :key="'messages-'.$campaign->id" />

        @endif


        @if($tab === 'analytics')

        <div class="space-y-8">

            {{-- Performance Overview --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

                {{-- Total --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="text-sm text-slate-500">
                        Total Messages
                    </div>

                    <div class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format($stats['total']) }}
                    </div>
                </div>

                {{-- Processed --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="text-sm text-slate-500">
                        Processed
                    </div>

                    <div class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format($stats['processed']) }}
                    </div>

                    <div class="mt-2 text-sm text-slate-500">
                        {{ $stats['progress'] }}% complete
                    </div>
                </div>

                {{-- Successful --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="text-sm text-slate-500">
                        Successful
                    </div>

                    <div class="mt-2 text-3xl font-bold text-emerald-600">
                        {{ number_format($stats['successful']) }}
                    </div>

                    <div class="mt-2 text-sm text-slate-500">
                        {{ $stats['success_rate'] }}% success rate
                    </div>
                </div>

                {{-- Failed --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="text-sm text-slate-500">
                        Failed
                    </div>

                    <div class="mt-2 text-3xl font-bold text-red-600">
                        {{ number_format($stats['failed']) }}
                    </div>

                    <div class="mt-2 text-sm text-slate-500">
                        {{ $stats['failure_rate'] }}% failure rate
                    </div>
                </div>

            </div>


            {{-- Progress --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex items-center justify-between mb-3">

                    <div>
                        <h3 class="font-semibold text-slate-900">
                            Campaign Progress
                        </h3>

                        <p class="text-sm text-slate-500">
                            Messages that have reached a final state
                        </p>
                    </div>

                    <div class="text-2xl font-bold text-amber-600">
                        {{ $stats['progress'] }}%
                    </div>

                </div>

                <div class="h-3 rounded-full bg-slate-100 overflow-hidden">

                    <div class="h-full bg-amber-500 rounded-full transition-all duration-500"
                        style="width: {{ $stats['progress'] }}%"></div>

                </div>

                <div class="mt-3 flex justify-between text-sm text-slate-500">

                    <span>
                        {{ number_format($stats['processed']) }}
                        processed
                    </span>

                    <span>
                        {{ number_format($stats['active']) }}
                        active
                    </span>

                </div>

            </div>


            {{-- Delivery Performance --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Delivery Rate --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6">

                    <div class="flex items-center justify-between">

                        <div>
                            <h3 class="font-semibold text-slate-900">
                                Delivery Rate
                            </h3>

                            <p class="text-sm text-slate-500">
                                Sent messages successfully delivered
                            </p>
                        </div>

                        <div class="text-3xl font-bold text-emerald-600">
                            {{ $stats['delivery_rate'] }}%
                        </div>

                    </div>

                    <div class="mt-5 h-2 rounded-full bg-slate-100 overflow-hidden">

                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $stats['delivery_rate'] }}%">
                        </div>

                    </div>

                </div>


                {{-- Read Rate --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6">

                    <div class="flex items-center justify-between">

                        <div>
                            <h3 class="font-semibold text-slate-900">
                                Read Rate
                            </h3>

                            <p class="text-sm text-slate-500">
                                Delivered messages that were read
                            </p>
                        </div>

                        <div class="text-3xl font-bold text-purple-600">
                            {{ $stats['read_rate'] }}%
                        </div>

                    </div>

                    <div class="mt-5 h-2 rounded-full bg-slate-100 overflow-hidden">

                        <div class="h-full bg-purple-500 rounded-full" style="width: {{ $stats['read_rate'] }}%"></div>

                    </div>

                </div>

            </div>

            {{-- Delivery Funnel --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="mb-6">

                    <h3 class="font-semibold text-slate-900">
                        Delivery Funnel
                    </h3>

                    <p class="text-sm text-slate-500">
                        Message progression through the delivery lifecycle
                    </p>

                </div>

                <div class="space-y-5">

                    {{-- Sent --}}
                    <div>

                        <div class="flex items-center justify-between mb-2">

                            <span class="text-sm font-medium text-slate-700">
                                Sent
                            </span>

                            <span class="text-sm font-semibold text-slate-900">
                                {{ number_format($stats['sent_funnel']) }}
                            </span>

                        </div>

                        <div class="h-3 rounded-full bg-slate-100 overflow-hidden">

                            <div class="h-full rounded-full bg-blue-500 transition-all duration-500" style="width: {{ $stats['total'] > 0
                        ? ($stats['sent_funnel'] / $stats['total']) * 100
                        : 0 }}%"></div>

                        </div>

                    </div>


                    {{-- Delivered --}}
                    <div>

                        <div class="flex items-center justify-between mb-2">

                            <span class="text-sm font-medium text-slate-700">
                                Delivered
                            </span>

                            <span class="text-sm font-semibold text-slate-900">
                                {{ number_format($stats['delivered_funnel']) }}
                            </span>

                        </div>

                        <div class="h-3 rounded-full bg-slate-100 overflow-hidden">

                            <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $stats['total'] > 0
                        ? ($stats['delivered_funnel'] / $stats['total']) * 100
                        : 0 }}%"></div>

                        </div>

                    </div>


                    {{-- Read --}}
                    <div>

                        <div class="flex items-center justify-between mb-2">

                            <span class="text-sm font-medium text-slate-700">
                                Read
                            </span>

                            <span class="text-sm font-semibold text-slate-900">
                                {{ number_format($stats['read_funnel']) }}
                            </span>

                        </div>

                        <div class="h-3 rounded-full bg-slate-100 overflow-hidden">

                            <div class="h-full rounded-full bg-purple-500 transition-all duration-500" style="width: {{ $stats['total'] > 0
                        ? ($stats['read_funnel'] / $stats['total']) * 100
                        : 0 }}%"></div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Retry Metrics --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="flex items-center justify-between mb-6">

                    <div>
                        <h3 class="font-semibold text-slate-900">
                            Retry Metrics
                        </h3>

                        <p class="text-sm text-slate-500">
                            Messages that required one or more retry attempts
                        </p>
                    </div>

                    <div class="text-3xl font-bold text-amber-600">
                        {{ $stats['retry_rate'] }}%
                    </div>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div class="rounded-xl bg-slate-50 p-5">

                        <div class="text-sm text-slate-500">
                            Messages Retried
                        </div>

                        <div class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($stats['retried_messages']) }}
                        </div>

                    </div>

                    <div class="rounded-xl bg-slate-50 p-5">

                        <div class="text-sm text-slate-500">
                            Total Retry Attempts
                        </div>

                        <div class="mt-2 text-2xl font-bold text-slate-900">
                            {{ number_format($stats['total_retry_attempts']) }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- Message Status Breakdown --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="mb-6">

                    <h3 class="font-semibold text-slate-900">
                        Message Status Breakdown
                    </h3>

                    <p class="text-sm text-slate-500">
                        Current status of every campaign message
                    </p>

                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-4">

                    <div class="text-center">
                        <div class="text-2xl font-bold text-slate-700">
                            {{ $stats['pending'] }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Pending
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $stats['queued'] }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Queued
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-2xl font-bold text-amber-600">
                            {{ $stats['sending'] }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Sending
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $stats['sent'] }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Sent
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-600">
                            {{ $stats['delivered'] }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Delivered
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ $stats['read'] }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Read
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">
                            {{ $stats['failed'] }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Failed
                        </div>
                    </div>

                </div>

            </div>

        </div>

        @endif


        @if($tab === 'activity')

        <livewire:vendor.campaign-activity :campaign="$campaign" />

        @endif

    </div>

</div>