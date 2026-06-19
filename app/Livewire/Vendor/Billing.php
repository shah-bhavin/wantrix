<?php

namespace App\Livewire\Vendor;

use App\Services\VendorBillingService;
use Livewire\Component;

class Billing extends Component
{
    public array $data = [];
    
    public function mount(VendorBillingService $billingService): void
    {
        $this->data = $billingService->getData(auth()->user());
    }

    public function render()
    {
        return view('livewire.vendor.billing')->layout('layouts.vendor');
    }
}
