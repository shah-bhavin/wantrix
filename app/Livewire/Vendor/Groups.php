<?php

namespace App\Livewire\Vendor;

use App\Models\Group;
use Livewire\Component;

class Groups extends Component
{
    public bool $showCreateModal = false;
    public string $name = '';
    public string $description = '';

    public function createGroup(): void
    {
        $this->validate([
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
        ]);

        Group::create([
            'vendor_id' => auth()->user()->vendor_id,
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description']);
        $this->showCreateModal = false;

        session()->flash('success', 'Group created successfully.');
    }


    public function render()
    {
        $groups = Group::where('vendor_id', auth()->user()->vendor_id)->latest()->get();
        return view('livewire.vendor.groups', compact('groups'))->layout('layouts.vendor');
    }
}
