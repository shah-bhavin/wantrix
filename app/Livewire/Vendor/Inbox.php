<?php

namespace App\Livewire\Vendor;

use Livewire\Component;

class Inbox extends Component
{
    public function render()
    {
        return view('livewire.vendor.inbox')
            ->layout('layouts.vendor');
    }
}