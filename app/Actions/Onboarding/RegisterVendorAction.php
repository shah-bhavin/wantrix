<?php

namespace App\Actions\Onboarding;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterVendorAction
{
    public function execute(array $data): Vendor
    {
        return DB::transaction(function () use ($data) {
            $vendor = Vendor::create([
                'name' => $data['company_name'],
                'status' => 'active',
            ]);

            $user = User::create([
                'vendor_id' => $vendor->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'status' => 'active',
            ]);

            $user->assignRole('vendor_admin');

            app(AssignTrialSubscriptionAction::class)->execute($vendor);

            return $vendor;
        });
    }
}
