<?php

namespace App\Livewire\Vendor;

use App\Actions\Campaigns\CancelCampaignAction;
use App\Actions\Campaigns\DispatchCampaignAction;
use App\Actions\Campaigns\PauseCampaignAction;
use App\Actions\Campaigns\ResumeCampaignAction;
use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Services\CampaignStatisticsService;
use App\Services\CampaignWorkflowService;
use Livewire\Component;

class CampaignShow extends Component
{
    public Campaign $campaign;
    public string $tab = 'overview';

    public array $stats = [];
    public int $progress = 0;

    protected CampaignStatisticsService $statistics;

    public function mount(Campaign $campaign, CampaignStatisticsService $statistics): void
    {
        abort_unless(
            $campaign->vendor_id === auth()->user()->vendor_id,
            403
        );

        $this->campaign = $campaign;
        $this->statistics = $statistics;
        $this->campaign->load([
            'group.contacts',
            'template',
        ]);

        $this->loadStats();
    }

    public function sendCampaign(): void
    {
        $this->campaign->refresh();

        if (! $this->campaign->canSend()) {

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Campaign already processed.'
            );

            return;
        }

        app(DispatchCampaignAction::class)->execute($this->campaign);

        $this->refreshCampaign();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Campaign queued successfully.'
        );
    }

    public function pauseCampaign(): void
    {
        $success = app(PauseCampaignAction::class)
            ->execute($this->campaign);

        $this->refreshCampaign();

        if (! $success) {

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Unable to pause campaign.'
            );

            return;
        }

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Campaign paused successfully.'
        );
    }

    public function resumeCampaign(): void
    {
        $success = app(ResumeCampaignAction::class)
            ->execute($this->campaign);

        $this->refreshCampaign();

        if (! $success) {

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Unable to resume campaign.'
            );

            return;
        }

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Campaign resumed successfully.'
        );
    }

    public function cancelCampaign(): void
    {
        $success = app(CancelCampaignAction::class)
            ->execute($this->campaign);

        $this->refreshCampaign();

        if (! $success) {

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Unable to cancel campaign.'
            );

            return;
        }

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Campaign cancelled successfully.'
        );
    }

    public function generateMessages(): void
    {
        try {
            app(CampaignWorkflowService::class)->generateMessages($this->campaign);
        } catch (\Exception $e) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: $e->getMessage()
            );

            return;
        }

        $this->campaign = $this->campaign->fresh([
            'group',
            'template',
            'messages',
        ]);
        
        $this->loadStats();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Messages generated successfully.'
        );
    }

    public function loadStats(): void
    {
        $this->campaign->refresh();

        $this->stats = app(CampaignStatisticsService::class)
            ->get($this->campaign);
    }

    public function refreshCampaign(): void
    {
        $this->campaign->refresh();

        $this->loadStats();
    }

    public function shouldPoll(): bool
    {
        return (
                $this->campaign->status === CampaignStatus::DRAFT
                && $this->campaign->messages()->doesntExist()
            )
            ||
            $this->campaign->status === CampaignStatus::PROCESSING
            ||
            $this->campaign->status === CampaignStatus::PAUSED;
    }

    public function changeTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        $this->campaign = Campaign::with([
            'group',
            'template',
        ])->find($this->campaign->id);

        return view('livewire.vendor.campaign-show')
            ->layout('layouts.vendor');
    }
}
