<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Actions\Onboarding\RegisterVendorAction;

class RegisterCompany extends Component
{
    public string $company_name = '';
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'max:255'],
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    public function register(): mixed
    {
        $data = $this->validate();
        $vendor = app(RegisterVendorAction::class)->execute($data);
        $user = User::where('vendor_id', $vendor->id)->first();

        Auth::login($user);

        return redirect()->route('vendor.dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register-company')->layout('layouts.guest');
    }

}
