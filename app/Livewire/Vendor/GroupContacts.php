<?php

namespace App\Livewire\Vendor;

use App\Models\Contact;
use App\Models\Group;
use Livewire\Component;

class GroupContacts extends Component
{
    public Group $group;
    public array $selectedContacts = [];

    public function mount(Group $group): void
    {
        abort_if($group->vendor_id !== auth()->user()->vendor_id, 403);

        $this->group = $group;
        $this->selectedContacts = $group->contacts()->pluck('contacts.id')->toArray();
    }

    public function save(): void
    {
        $this->group->contacts()->sync($this->selectedContacts);

        session()->flash('success', 'Contacts updated successfully.');
    }

    public function render()
    {
        $contacts = Contact::query()
            ->where('vendor_id', auth()->user()->vendor_id)
            ->orderBy('name')
            ->get();

        return view('livewire.vendor.group-contacts', compact('contacts'))->layout('layouts.vendor');
    }
}
