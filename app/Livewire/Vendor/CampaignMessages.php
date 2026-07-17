<?php

namespace App\Livewire\Vendor;

use App\Enums\MessageStatus;
use App\Jobs\ProcessMessageJob;
use App\Livewire\Concerns\WithDataTable;
use App\Models\Campaign;
use App\Services\CampaignWorkflowService;
use Livewire\Attributes\Url;
use Livewire\Component;

class CampaignMessages extends Component
{
    use WithDataTable;

    public Campaign $campaign;

    #[Url]
    public string $status = '';

    public array $selected = [];

    public bool $selectAll = false;

    public function mount(Campaign $campaign): void
    {
        abort_unless(
            $campaign->vendor_id === auth()->user()->vendor_id,
            403
        );

        $this->campaign = $campaign;
    }

    public function updatingStatus(): void
    {
        $this->resetPage();

        $this->selected = [];

        $this->selectAll = false;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();

        $this->selected = [];

        $this->selectAll = false;
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
                function ($query) {
                    $query->whereHas('contact', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%")
                            ->orWhere(
                                'phone_number',
                                'like',
                                "%{$this->search}%"
                            );
                    });
                }
            )
            ->when(
                $this->status,
                fn($query) =>
                $query->where('status', $this->status)
            )
            ->orderBy(
                $this->sortField,
                $this->sortDirection
            )
            ->orderBy('id', 'desc');
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selected = $this->messages()
                ->paginate($this->perPage)
                ->pluck('id')
                ->toArray();

            return;
        }

        $this->selected = [];
    }

    public function retrySelected(): void
    {
        $this->campaign->refresh();

        if ($this->campaign->status->isFinished()) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Messages cannot be retried because this campaign is already finished.'
            );

            return;
        }

        if (empty($this->selected)) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Please select at least one message.'
            );

            return;
        }

        $count = app(CampaignWorkflowService::class)
            ->retryFailedMessages(
                $this->campaign,
                $this->selected
            );

        if ($count === 0) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'No selected messages are eligible for retry.'
            );

            return;
        }

        $this->selected = [];

        $this->selectAll = false;

        $this->dispatch(
            'notify',
            type: 'success',
            message: "{$count} message(s) queued for retry."
        );
    }

    public function deleteSelected(): void
    {
        if (! $this->campaign->canDeleteMessages()) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Messages cannot be deleted after campaign processing has started.'
            );

            return;
        }

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
            'messages' => $this->messages()
                ->paginate($this->perPage),
        ]);
    }
}
