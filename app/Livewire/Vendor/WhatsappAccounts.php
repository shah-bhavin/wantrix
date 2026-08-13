<?php

namespace App\Livewire\Vendor;

use App\Enums\WhatsappAccountStatus;
use App\Services\Meta\EmbeddedSignupService;
use App\Services\Whatsapp\WhatsappService;
use App\Support\UsageLimit;
use Illuminate\Support\Facades\Log;
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
    public ?string $verified_name = '';
    public ?string $display_phone_number = '';
    public ?string $quality_rating = '';
    public ?string $verification_status = '';
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
    public string $testRecipient = '';
    public string $testMessage = '';
    public bool $sendingTestMessage = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'min:2'],
            'provider' => ['required'],
            'phone_number_id' => ['required'],
            'access_token' => ['required'],
        ];
    }

    public function sendTestMessage(
        WhatsappService $whatsapp
    ): void {
        $this->validate([
            'testRecipient' => [
                'required',
                'regex:/^\+?[1-9]\d{7,14}$/',
            ],
            'testMessage' => [
                'required',
                'string',
                'max:4096',
            ],
        ]);

        $this->sendingTestMessage = true;

        try {
            $account = auth()
                ->user()
                ->vendor
                ->whatsappAccounts()
                ->where('is_active', true)
                ->where('status', WhatsappAccountStatus::CONNECTED)
                ->first();

            if (! $account) {
                session()->flash(
                    'error',
                    'No active connected WhatsApp account found.'
                );

                return;
            }

            Log::info('WHATSAPP TEST MESSAGE STARTED', [
                'account_id' => $account->id,
                'phone_number_id' => $account->phone_number_id,
                'waba_id' => $account->waba_id,
                'recipient' => $this->testRecipient,
            ]);

            $response = $whatsapp->sendText(
                $account,
                $this->testRecipient,
                $this->testMessage
            );

            if (! ($response['success'] ?? false)) {
                $errorMessage = data_get(
                    $response,
                    'error.error.message',
                    'Unable to send WhatsApp message.'
                );

                Log::error('WHATSAPP TEST MESSAGE FAILED', [
                    'account_id' => $account->id,
                    'recipient' => $this->testRecipient,
                    'response' => $response,
                ]);

                session()->flash(
                    'error',
                    $errorMessage
                );

                return;
            }

            Log::info('WHATSAPP TEST MESSAGE SENT', [
                'account_id' => $account->id,
                'recipient' => $this->testRecipient,
                'message_id' => $response['message_id'] ?? null,
            ]);

            session()->flash(
                'success',
                'Test WhatsApp message sent successfully.'
            );

            $this->testRecipient = '';
            $this->testMessage = '';
        } catch (\Throwable $e) {

            Log::error('WHATSAPP TEST MESSAGE EXCEPTION', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            session()->flash(
                'error',
                'Unable to send WhatsApp message: '
                    . $e->getMessage()
            );
        } finally {

            $this->sendingTestMessage = false;
        }
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
        $this->display_phone_number = $account->display_phone_number;
        $this->provider = $account->provider;
        $this->phone_number_id = $account->phone_number_id ?? '';
        $this->access_token = $account->access_token ?? '';
        $this->waba_id = $account->waba_id ?? '';
        $this->showCreateModal = true;
        $this->phone_number = $account->phone_number;
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
            'access_token' => ['required'],
        ]);

        $this->verifyingConnection = true;

        try {
            $account = new \App\Models\WhatsappAccount([
                'phone_number_id' => $this->phone_number_id,
                'access_token' => $this->access_token,
            ]);

            $response = $whatsapp->verify($account);

            if (! $response['success']) {
                session()->flash(
                    'error',
                    data_get(
                        $response,
                        'error.error.message',
                        'Unable to verify connection.'
                    )
                );

                return;
            }

            $this->verified_name =
                $response['data']['verified_name'] ?? '';

            if (blank($this->name)) {
                $this->name = $this->verified_name;
            }

            $this->display_phone_number =
                $response['data']['display_phone_number'] ?? '';

            $this->phone_number =
                $this->display_phone_number;

            $this->quality_rating =
                $response['data']['quality_rating'] ?? '';

            $this->verification_status =
                $response['data']['code_verification_status'] ?? '';

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
        } finally {

            $this->verifyingConnection = false;
        }
    }

    #[On('meta-code-received')]
    public function receiveAuthorizationCode(
        string $code,
        EmbeddedSignupService $signup,
        ?string $wabaId = null,
        ?string $phoneNumberId = null,
        ?string $businessId = null
    ): void {

        Log::info('META EMBEDDED SIGNUP DATA RECEIVED', [
            'code_received' => ! empty($code),
            'code_length' => strlen($code),
            'waba_id' => $wabaId,
            'phone_number_id' => $phoneNumberId,
            'business_id' => $businessId,
        ]);

        $this->embeddedSignupLoading = true;

        try {

            if (
                blank($wabaId) ||
                blank($phoneNumberId)
            ) {

                session()->flash(
                    'error',
                    'Meta did not return WABA ID or Phone Number ID.'
                );

                return;
            }

            $vendor = auth()->user()->vendor;

            $result = $signup->finish(
                $vendor,
                $code,
                $wabaId,
                $phoneNumberId,
                $businessId
            );

            Log::info(
                'META EMBEDDED SIGNUP FINISH RESULT',
                [
                    'success' => $result->success,
                    'error' => $result->error,
                ]
            );

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
        } catch (\Throwable $e) {

            Log::error(
                'META EMBEDDED SIGNUP EXCEPTION',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            session()->flash(
                'error',
                'Unable to complete WhatsApp connection: '
                    . $e->getMessage()
            );
        } finally {

            $this->embeddedSignupLoading = false;
        }
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
