<?php

namespace App\Livewire\Vendor;

use App\Actions\Campaigns\DispatchCampaignAction;
use App\Actions\Campaigns\GenerateCampaignMessagesAction;
use App\Actions\Campaigns\PauseCampaignAction;
use App\Actions\Campaigns\ResumeCampaignAction;
use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Services\CampaignStatisticsService;
use App\Services\CampaignTimelineService;
use Livewire\Component;

class CampaignShow extends Component
{
    public Campaign $campaign;
    public string $tab = 'overview';

    public array $stats = [];
    public int $progress = 0;
    public array $timeline = [];

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
        $this->loadTimeline();
    }


    public function sendCampaign(): void
    {
        if (! $this->campaign->canSend()) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Campaign already processed.'
            );
            return;
        }

        app(DispatchCampaignAction::class)
            ->execute($this->campaign);
        $this->loadStats();
        $this->campaign->refresh();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Campaign queued successfully.'
        );
    }

    public function pauseCampaign(): void
    {
        app(PauseCampaignAction::class)
            ->execute($this->campaign);

        $this->refreshCampaign();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Campaign paused.'
        );
    }

    public function resumeCampaign(): void
    {
        app(ResumeCampaignAction::class)
            ->execute($this->campaign);

        $this->refreshCampaign();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Campaign resumed.'
        );
    }
    
    public function generateMessages(): void
    {     
        if (! $this->campaign->canGenerateMessages()) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Messages already generated.'
            );
            return;
        }       

        try {
            app(GenerateCampaignMessagesAction::class)->execute($this->campaign);
        } catch (\Exception $e) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: $e->getMessage()
            );
            return;
        }



            $this->loadStats();
            $this->campaign->refresh();
        
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

    public function loadTimeline(): void
    {
        $this->timeline = app(CampaignTimelineService::class)
            ->get($this->campaign);
    }

    public function refreshCampaign(): void
    {
        $this->campaign->refresh();

        $this->loadStats();

        $this->loadTimeline();
    }

    public function shouldPoll(): bool
    {
        return in_array(
            $this->campaign->status,
            [
                CampaignStatus::PROCESSING,
                CampaignStatus::SCHEDULED,
            ]
        );
    }

    public function changeTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.vendor.campaign-show')->layout('layouts.vendor');
    }

}
