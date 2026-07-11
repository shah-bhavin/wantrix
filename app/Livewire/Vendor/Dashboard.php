<?php

namespace App\Livewire\Vendor;

use App\Services\DashboardService;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];

    public function mount(DashboardService $dashboardService): void
    {
        $this->stats = $dashboardService->getData(auth()->user());
    }

    public function render()
    {
        return view('livewire.vendor.dashboard')->layout('layouts.vendor', ['title' => 'Dashboard']);
    }
}
