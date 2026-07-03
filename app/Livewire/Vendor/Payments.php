<?php

namespace App\Livewire\Vendor;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class Payments extends Component
{
    use WithPagination;
    public function render()
    {
        $vendor = auth()->user()->vendor;
        $payments = Payment::query()->where('vendor_id', $vendor->id)->latest()->paginate(10);

        return view('livewire.vendor.payments', compact('payments'))->layout('layouts.vendor');

    }
}
