<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use App\Models\Invoice;
use Livewire\WithPagination;

class Invoices extends Component
{
    use WithPagination;
    public function render()
    {
        $vendor = auth()->user()->vendor;
        $invoices = Invoice::query()->where('vendor_id', $vendor->id)->latest()->paginate(10);

        return view('livewire.vendor.invoices', compact('invoices'))->layout('layouts.vendor');
    }
}
