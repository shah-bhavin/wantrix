<?php

namespace App\Livewire\Vendor;

use Livewire\Component;

class MetaSetup extends Component
{
    public function render()
    {
        $account = auth()->user()->vendor->whatsappAccounts()->where('is_active', true)->first();
        return view('livewire.vendor.meta-setup', compact('account'))->layout('layouts.vendor');
    }
}
