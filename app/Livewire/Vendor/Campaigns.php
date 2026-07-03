<?php

namespace App\Livewire\Vendor;

use App\Models\Campaign;
use App\Models\Group;
use App\Models\Template;
use App\Support\UsageLimit;
use Livewire\Component;

class Campaigns extends Component
{
    public bool $showCreateModal = false;
    public string $name = '';
    public string $group_id = '';
    public string $template_id = '';
    public string $whatsapp_account_id = '';
    public ?string $scheduled_at = null;

    public function createCampaign(): void
    {
        $this->validate([
            'name' => ['required'],
            'group_id' => ['required'],
            'template_id' => ['required'],
            'whatsapp_account_id' => ['required'],
            'scheduled_at' => ['nullable'],
        ]);

        if (!UsageLimit::canCreateCampaign(auth()->user()->vendor)) {
            session()->flash('error', 'Campaign limit reached. Upgrade your plan.');
            return;
        }


        Campaign::create([
            'vendor_id' => auth()->user()->vendor_id,
            'group_id' => $this->group_id,
            'template_id' => $this->template_id,
            'whatsapp_account_id' => $this->whatsapp_account_id,
            'name' => $this->name,
            'status' => $this->scheduled_at
                ? 'scheduled'
                : 'draft',
            'scheduled_at' => $this->scheduled_at,
        ]);

        $this->reset(['name', 'group_id', 'template_id', 'whatsapp_account_id']);
        $this->showCreateModal = false;

        session()->flash('success', 'Campaign created successfully.');
    }

    public function render()
    {
        $vendor = auth()->user()->vendor;

        $campaigns = $vendor->campaigns()
            ->with(['group', 'template', 'whatsappAccount'])
            ->latest()
            ->get();

        $groups = $vendor->groups()->orderBy('name')->get();
        $templates = $vendor->templates()->orderBy('name')->get();
        $whatsappAccounts = $vendor
            ->whatsappAccounts()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('livewire.vendor.campaigns', compact('campaigns', 'groups', 'templates', 'whatsappAccounts'))->layout('layouts.vendor');
    }
}
