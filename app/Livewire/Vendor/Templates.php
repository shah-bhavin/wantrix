<?php

namespace App\Livewire\Vendor;

use App\Services\Whatsapp\TemplateSyncService;
use Livewire\Component;

class Templates extends Component
{
    public string $search = '';
    public ?int $selectedAccount = null;

    public function syncTemplates(
        TemplateSyncService $service
    ) {
        $account = auth()
            ->user()
            ->vendor
            ->whatsappAccounts()
            ->find($this->selectedAccount);

        if (! $account) {

            session()->flash(
                'error',
                'Please select a WhatsApp Account.'
            );

            return;
        }

        $result = $service->sync(
            $account
        );
        if (!$result['success']) {

            session()->flash(
                'error',
                'Unable to sync templates.'
            );

            return;
        }
        session()->flash(

            'success',

            "{$result['count']} templates synced successfully."

        );
    }

    public function render()
    {
        $accounts = auth()
            ->user()
            ->vendor
            ->whatsappAccounts()
            ->orderBy('name')
            ->get();

        $templates = auth()->user()
            ->vendor
            ->whatsappTemplates()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('language', 'like', "%{$this->search}%")
                        ->orWhere('category', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->get();

        return view(
            'livewire.vendor.templates',
            compact('templates', 'accounts')
        )->layout('layouts.vendor');
    }

    public function mount(): void
    {
        $this->selectedAccount = auth()
            ->user()
            ->vendor
            ->whatsappAccounts()
            ->value('id');
    }
}
