<?php

namespace App\Livewire\Vendor;

use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignMessages extends Component
{

    public Campaign $campaign;
    public string $search = '';
    public string $status = '';
    public int $perPage = 20;
    use WithPagination;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function messages()
    {
        return $this->campaign
            ->messages()
            ->with('contact')
            ->when(
                $this->search,
                fn($query) =>
                $query->whereHas('contact', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('phone_number', 'like', "%{$this->search}%");
                })
            )
            ->when(
                $this->status,
                fn($query) =>
                $query->where('status', $this->status)
            )
            ->latest();
    }
    public function render()
    {
        return view('livewire.vendor.campaign-messages', [
            'messages' => $this->messages()->paginate($this->perPage),
        ]);
    }
}
