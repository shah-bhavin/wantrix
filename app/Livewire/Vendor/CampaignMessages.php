<?php

namespace App\Livewire\Vendor;

use App\Livewire\Concerns\WithDataTable;
use App\Models\Campaign;
use Livewire\Attributes\Url;
use Livewire\Component;

class CampaignMessages extends Component
{

    public Campaign $campaign;

    #[Url]
    public string $status = '';

    #[Url]
    public string $sort = 'created_at';

    #[Url]
    public string $direction = 'desc';

    public array $selected = [];

    public bool $selectAll = false;

    use WithDataTable;
    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }
    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function getTotalProperty(): int
    {
        return $this->campaign
            ->messages()
            ->count();
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
            ->orderBy(
                $this->sortField,
                $this->sortDirection
            );
    }

    public function retrySelected(): void
    {
        $this->dispatch(
            'notify',
            type: 'info',
            message: 'Retry feature is coming in the next lesson.'
        );
    }

    public function deleteSelected(): void
    {
        $this->campaign
            ->messages()
            ->whereIn('id', $this->selected)
            ->delete();

        $this->selected = [];

        $this->selectAll = false;

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Messages deleted successfully.'
        );
    }
    
    public function render()
    {
        return view('livewire.vendor.campaign-messages', [
            'messages' => $this->messages()->paginate($this->perPage),
        ]);
    }
}
