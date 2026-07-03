<?php

namespace App\Livewire\Vendor;

use App\Models\User;
use App\Support\UsageLimit;
use Livewire\Component;

class TeamMembers extends Component
{
    public bool $showCreateModal = false;
    public string $name = '';
    public string $email = '';
    public string $role = 'agent';
    public ?int $editingId = null;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

    protected function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'role' => ['required'],
        ];
    }

    public function createMember(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            User::where('vendor_id', auth()->user()->vendor_id)->findOrFail($this->editingId)->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
            ]);

            session()->flash('success', 'Member updated successfully.');
        } else {
            if (!UsageLimit::canCreateUser(auth()->user()->vendor)) {
                session()->flash('error', 'User limit reached. Upgrade your plan.');
                return;
            }


            User::create([
                'vendor_id' => auth()->user()->vendor_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => bcrypt('password'),
            ]);

            session()->flash('success', 'Member created successfully.');
        }

        $this->reset(['name', 'email', 'editingId']);
        $this->role = 'agent';
        $this->showCreateModal = false;
    }


    public function editMember($id): void
    {
        $member = User::where('vendor_id', auth()->user()->vendor_id)->findOrFail($id);

        $this->editingId = $member->id;
        $this->name = $member->name;
        $this->email = $member->email;
        $this->role = $member->role;

        $this->showCreateModal = true;
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteMember(): void
    {
        $member = User::where('vendor_id', auth()->user()->vendor_id)->findOrFail($this->deleteId);

        if ($member->role === 'owner') {
            session()->flash('error', 'Owner cannot be deleted.');
            return;
        }

        $member->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;

        session()->flash('success', 'Member deleted successfully.');
    }


    public function render()
    {
        $members = User::where('vendor_id', auth()->user()->vendor_id)->latest()->get();

        return view('livewire.vendor.team-members', compact('members'))->layout('layouts.vendor');
    }
}
