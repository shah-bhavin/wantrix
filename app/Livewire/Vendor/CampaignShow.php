<?php

namespace App\Livewire\Vendor;

use App\Actions\Campaigns\DispatchCampaignAction;
use App\Actions\Campaigns\GenerateCampaignMessagesAction;
use App\Models\Campaign;
use Livewire\Component;

class CampaignShow extends Component
{
    public Campaign $campaign;

    public int $totalMessages = 0;
    public int $pendingMessages = 0;
    public int $sentMessages = 0;
    public int $deliveredMessages = 0;
    public int $failedMessages = 0;

    // public function mount(Campaign $campaign)
    // {
    //     $this->campaign = $campaign;

    //     $this->loadStats();
    // }


    public function sendCampaign(): void
    {
        if ($this->campaign->status !== 'draft') {

            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Campaign already processed.'
            );
            return;
        }

        app(DispatchCampaignAction::class)
            ->execute($this->campaign);
        
        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Campaign queued successfully.'
        );
    }


    
    public function generateMessages(): void
    {     
        if ($this->campaign->messages()->exists()) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Messages already generated.'
            );
            return;
        }       

        app(GenerateCampaignMessagesAction::class)
            ->execute($this->campaign);
        
        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Messages generated successfully.'
        );
    }

    public function loadStats(): void 
    { 
        $this->campaign->refresh(); 
        
        $this->totalMessages = $this->campaign->messages()->count(); 
        $this->pendingMessages = $this->campaign->messages()->where('status', 'pending')->count(); 
        $this->sentMessages = $this->campaign->messages()->where('status', 'sent')->count(); 
        $this->deliveredMessages = $this->campaign->messages()->where('status', 'delivered')->count(); 
        $this->failedMessages = $this->campaign->messages()->where('status', 'failed')->count(); 
    }

    public function render()
    {
        return view('livewire.vendor.campaign-show')->layout('layouts.vendor');
    }

}
