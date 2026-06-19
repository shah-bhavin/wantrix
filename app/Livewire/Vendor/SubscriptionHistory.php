<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SubscriptionChange;

class SubscriptionHistory extends Component
{
    use WithPagination;

    public function render()
    {
        $vendor = auth()->user()->vendor;
        $changes = SubscriptionChange::query()->where('vendor_id', $vendor->id)->with(['oldPlan', 'newPlan'])->latest()->paginate(10);

        return view('livewire.vendor.subscription-history', compact('changes'))->layout('layouts.vendor');
    }
}
