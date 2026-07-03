<?php

namespace App\Livewire\Vendor;

use App\Actions\Contacts\CreateContactAction;
use App\Enums\ContactStatus;
use App\Models\Contact;
use App\Support\UsageLimit;
use Livewire\Component;
use Livewire\WithPagination;

class Contacts extends Component
{
    use WithPagination;
    public bool $showCreateModal = false;
    public string $name = '';
    public string $phone_number = '';
    public string $email = '';
    public string $company = '';
    public string $status = 'active';
    public string $search = '';
    
    public bool $showEditModal = false;
    public ?int $editingContactId = null;
    public ?int $deletingContactId = null;

    public array $selectedTags = [];
    
    protected function rules(): array
    {
        return [
            'name' => ['required'],
            'phone_number' => ['required'],
            'email' => ['nullable', 'email'],
            'company' => ['required'],
            'status' => ['required'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createContact(): void
    {
        $data = $this->validate();

        if (!UsageLimit::canCreateContact(auth()->user()->vendor)) {
            session()->flash('error', 'Contact limit reached. Upgrade your plan.');
            return;
        }


        $contact = app(CreateContactAction::class)->execute(
            auth()->user()->vendor,
            $data
        );

        $contact->tags()->sync(
            $this->selectedTags
        );

        $this->reset([
            'name',
            'phone_number',
            'email',
            'company',
        ]);

        $this->showCreateModal = false;

        session()->flash('success', 'Contact created successfully.');
    }

    public function render()
    {
        $vendorId = auth()->user()->vendor->id;

        $tags = auth()->user()->vendor->tags()->orderBy('name')->get();

        $totalContacts = Contact::where('vendor_id', $vendorId)->count();

        $activeContacts = Contact::where('vendor_id', $vendorId)
            ->where('status', ContactStatus::ACTIVE->value)
            ->count();

        $blockedContacts = Contact::where('vendor_id', $vendorId)
            ->where('status', ContactStatus::BLOCKED->value)
            ->count();

        $unsubscribedContacts = Contact::where('vendor_id', $vendorId)
            ->where('status', ContactStatus::UNSUBSCRIBED->value)
            ->count();


        $contacts = Contact::query()
            ->with(['tags', 'groups'])
            ->where('vendor_id', auth()->user()->vendor->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('company', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.vendor.contacts', [
            'contacts' => $contacts,
            'tags' => $tags,
            'totalContacts' => $totalContacts,
            'activeContacts' => $activeContacts,
            'blockedContacts' => $blockedContacts,
            'unsubscribedContacts' => $unsubscribedContacts,
        ])->layout('layouts.vendor');
    }

    public function editContact(int $id): void
    {
        
        $contact = Contact::findOrFail($id);
        $this->selectedTags = $contact
            ->tags()
            ->pluck('tags.id')
            ->toArray();
        $this->editingContactId = $contact->id;
        $this->name = $contact->name;
        $this->phone_number = $contact->phone_number;
        $this->email = $contact->email ?? '';
        $this->company = $contact->company ?? '';
        $this->status = $contact->status;
        $this->showEditModal = true;
    }

    public function updateContact(): void
    {
        $data = $this->validate();
        Contact::where('id', $this->editingContactId)->update($data);

        $contact = Contact::findOrFail($this->editingContactId);
        $contact->tags()->sync($this->selectedTags);

        $this->showEditModal = false;
        session()->flash('success', 'Contact updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingContactId = $id;
    }

    public function deleteContact(): void
    {
        Contact::where('id', $this->deletingContactId)->delete();
        $this->deletingContactId = null;
        session()->flash('success', 'Contact deleted successfully.');
    }



}
