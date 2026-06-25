<?php

namespace App\Livewire\Vendor;

use Livewire\Component;

class WhatsappAccounts extends Component
{
    public bool $showCreateModal = false;
    public string $name = '';
    public string $phone_number = '';
    public string $provider = 'meta';
    public string $phone_number_id = '';
    public string $access_token = '';
    public bool $is_active = false;
    public ?int $editingId = null;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

    protected function rules(): array
    {
        return [
            'name' => ['required'],
            'phone_number' => ['required'],
            'provider' => ['required'],
        ];
    }

    public function createAccount(): void
    {
        $data = $this->validate();

        if ($this->editingId) {

            auth()->user()
                ->vendor
                ->whatsappAccounts()
                ->findOrFail($this->editingId)
                ->update([
                    'name' => $this->name,
                    'phone_number' => $this->phone_number,
                    'provider' => $this->provider,
                    'phone_number_id' => $this->phone_number_id,
                    'access_token' => $this->access_token,
                ]);

        } else {

            auth()->user()
                ->vendor
                ->whatsappAccounts()
                ->create([
                    'name' => $this->name,
                    'phone_number' => $this->phone_number,
                    'provider' => $this->provider,
                    'phone_number_id' => $this->phone_number_id,
                    'access_token' => $this->access_token,
                    'is_active' => $this->is_active,
                ]);
        }

        $this->reset([
            'name',
            'phone_number',
            'phone_number_id',
            'access_token',
        ]);

        $this->provider = 'meta';
        $this->showCreateModal = false;

        session()->flash('success', 'WhatsApp account created.');
    }

    public function editAccount($id): void
    {
        $account = auth()->user()->vendor->whatsappAccounts()->findOrFail($id);

        $this->editingId = $account->id;
        $this->name = $account->name;
        $this->phone_number = $account->phone_number;
        $this->provider = $account->provider;
        $this->phone_number_id = $account->phone_number_id ?? '';
        $this->access_token = $account->access_token ?? '';

        $this->showCreateModal = true;
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;

        $this->showDeleteModal = true;
    }

    public function deleteAccount(): void
    {
        auth()->user()->vendor->whatsappAccounts()->findOrFail($this->deleteId)->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;

        session()->flash('success', 'WhatsApp account deleted successfully.');
    }




    public function activateAccount($accountId): void
    {
        $vendor = auth()->user()->vendor;

        $vendor->whatsappAccounts()->update([
            'is_active' => false,
        ]);

        $vendor->whatsappAccounts()
            ->where('id', $accountId)
            ->update([
                'is_active' => true,
            ]);

        session()->flash('success', 'Active account updated.');
    }


    public function render()
    {
        $accounts = auth()->user()->vendor->whatsappAccounts()->latest()->get();
        return view('livewire.vendor.whatsapp-accounts', compact('accounts'))->layout('layouts.vendor');
    }
}
