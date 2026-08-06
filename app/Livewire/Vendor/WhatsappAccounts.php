<?php

namespace App\Livewire\Vendor;

use App\Enums\WhatsappAccountStatus;
use App\Services\Meta\EmbeddedSignupService;
use App\Services\Whatsapp\WhatsappService;
use App\Support\UsageLimit;
use Livewire\Attributes\On;
use Livewire\Component;

class WhatsappAccounts extends Component
{
    public bool $showCreateModal = false;
    public string $search = '';
    public int $totalAccounts = 0;
    public int $connectedAccounts = 0;
    public int $pendingAccounts = 0;
    public int $activeAccounts = 0;

    public string $name = '';
    public string $phone_number = '';
    public string $waba_id = '';
    public string $verified_name = '';
    public string $display_phone_number = '';
    public string $quality_rating = '';
    public string $verification_status = '';
    public string $provider = 'meta';
    public string $phone_number_id = '';
    public string $access_token = '';
    public bool $is_active = false;
    public ?int $editingId = null;
    public bool $showDeleteModal = false;
    public bool $verifyingConnection = false;
    public ?int $deleteId = null;
    public bool $embeddedSignupLoading = false;
    public ?string $embeddedSignupCode = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'min:2'],
            'provider' => ['required'],
            'phone_number_id' => ['required'],
            'access_token' => ['required'],
        ];
    }
    public function connectWithMeta()
    {
        $this->dispatch(
            'open-meta-signup'
        );
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
                    'phone_number' => $this->phone_number,
                    'waba_id' => $this->waba_id,
                    'verified_name' => $this->verified_name,
                    'quality_rating' => $this->quality_rating,
                    'verification_status' => $this->verification_status,

                    'status' => $this->verified_name
                        ? WhatsappAccountStatus::CONNECTED
                        : WhatsappAccountStatus::PENDING,

                    'last_verified_at' => $this->verified_name
                        ? now()
                        : null,

                    'last_error' => null,

                    'is_active' => $this->is_active,
                ]);
        } else {
            if (!UsageLimit::canCreateWhatsappAccount(auth()->user()->vendor)) {
                session()->flash('error', 'WhatsApp account limit reached. Upgrade your plan.');
                return;
            }
            $isFirstAccount = ! auth()->user()
                ->vendor
                ->whatsappAccounts()
                ->exists();
            auth()->user()
                ->vendor
                ->whatsappAccounts()

                ->create([
                    'name' => $this->name,
                    'phone_number' => $this->phone_number,
                    'provider' => $this->provider,
                    'phone_number_id' => $this->phone_number_id,
                    'access_token' => $this->access_token,
                    'waba_id' => $this->waba_id,
                    'verified_name' => $this->verified_name,
                    'quality_rating' => $this->quality_rating,
                    'verification_status' => $this->verification_status,

                    'status' => $this->verified_name
                        ? WhatsappAccountStatus::CONNECTED
                        : WhatsappAccountStatus::PENDING,

                    'last_verified_at' => $this->verified_name
                        ? now()
                        : null,

                    'last_error' => null,

                    'is_active' => $isFirstAccount,
                ]);
        }

        $this->reset([
            'name',
            'phone_number',
            'phone_number_id',
            'access_token',
            'waba_id',
            'verified_name',
            'display_phone_number',
            'quality_rating',
            'verification_status',
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
        $this->waba_id = $account->waba_id ?? '';
        $this->showCreateModal = true;
        $this->display_phone_number = $account->display_phone_number;
        $this->verified_name = $account->verified_name;
        $this->quality_rating = $account->quality_rating;
        $this->verification_status = $account->verification_status;
        $this->is_active = $account->is_active;
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

    protected function loadStatistics(): void
    {
        $accounts = auth()->user()
            ->vendor
            ->whatsappAccounts();

        $this->totalAccounts = (clone $accounts)->count();

        $this->connectedAccounts = (clone $accounts)
            ->where('status', 'connected')
            ->count();

        $this->pendingAccounts = (clone $accounts)
            ->where('status', 'pending')
            ->count();

        $this->activeAccounts = (clone $accounts)
            ->where('is_active', true)
            ->count();
    }

    public function verifyConnection(WhatsappService $whatsapp): void
    {
        $this->validate([
            'phone_number_id' => ['required'],
            'access_token'    => ['required'],
        ]);

        $this->verifyingConnection = true;

        try {

            $response = $whatsapp->verify($account);

            $waba = $meta->discoverWaba(
                $this->phone_number_id,
                $this->access_token
            );

            if (
                $waba['success']
                && isset($waba['data']['whatsapp_business_account']['id'])
            ) {
                $this->waba_id =
                    $waba['data']['whatsapp_business_account']['id'];
            }

            if (! $response['success']) {

                session()->flash(
                    'error',
                    data_get(
                        $response,
                        'error.error.message',
                        'Unable to verify connection.'
                    )
                );

                $this->verifyingConnection = false;

                return;
            }

            $this->verified_name = $response['data']['verified_name'] ?? '';
            if (blank($this->name)) {

                $this->name = $this->verified_name;
            }
            $this->display_phone_number = $response['data']['display_phone_number'] ?? '';
            //$this->waba_id = $response['data']['whatsapp_business_account']['id'] ?? '';
            $this->phone_number = $this->display_phone_number;
            $this->quality_rating = $response['data']['quality_rating'] ?? '';
            $this->verification_status = $response['data']['code_verification_status'] ?? '';

            session()->flash(
                'success',
                'WhatsApp account verified successfully.'
            );
        } catch (\Throwable $e) {

            report($e);

            session()->flash(
                'error',
                $e->getMessage()
            );
        }

        $this->verifyingConnection = false;
    }

    #[On('meta-code-received')]
    public function receiveAuthorizationCode(string $code, EmbeddedSignupService $signup): void {

        $this->embeddedSignupLoading = true;
        $this->embeddedSignupCode = $code;
        $vendor = auth()->user()->vendor;
        $result = $signup->finish(
            $vendor,
            $code
        );

        $this->embeddedSignupLoading = false;

        if (! $result->success) {
            session()->flash(
                'error',
                $result->error
            );
            return;
        }

        session()->flash(
            'success',
            'WhatsApp account connected successfully.'
        );
        
        $this->loadStatistics();
    }

    public function render()
    {
        $this->loadStatistics();

        $accounts = auth()->user()
            ->vendor
            ->whatsappAccounts()
            ->when($this->search, function ($query) {

                $query->where(function ($query) {

                    $query
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhere('phone_number', 'like', "%{$this->search}%")
                        ->orWhere('display_phone_number', 'like', "%{$this->search}%")
                        ->orWhere('verified_name', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->get();

        return view(
            'livewire.vendor.whatsapp-accounts',
            compact('accounts')
        )->layout('layouts.vendor');
    }
}
