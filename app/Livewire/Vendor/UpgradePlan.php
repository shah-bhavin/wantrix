<?php

namespace App\Livewire\Vendor;

use App\Models\Plan;
use Livewire\Component;
use App\Actions\Billing\UpgradeSubscriptionAction;

class UpgradePlan extends Component
{
    public function render()
    {
        return view('livewire.vendor.upgrade-plan', [
            'plans' => Plan::query()->where('status', 'active')->orderBy('sort_order')->get(),
        ])->layout('layouts.vendor');
    }

    public function selectPlan(int $planId): void
    {
        $plan = Plan::findOrFail($planId);
        $vendor = auth()->user()->vendor;

        app(UpgradeSubscriptionAction::class)->execute($vendor, $plan);
        
        session()->flash('success', 'Invoice and payment created.');

        $this->redirect(route('vendor.billing.payments'));
    }


}
