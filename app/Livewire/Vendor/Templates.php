<?php

namespace App\Livewire\Vendor;

use App\Models\WhatsappTemplate;
use App\Services\Meta\MetaTemplateService;
use Livewire\Component;

class Templates extends Component
{
    public string $search = '';

    public function syncTemplates(
        MetaTemplateService $service
    ) {
        dd(
            $service->sync(
                auth()->user()->vendor
            )
        );
    }

    public function render()
    {
        $templates = auth()->user()
            ->vendor
            ->whatsappTemplates()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('language', 'like', "%{$this->search}%")
                        ->orWhere('category', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->get();

        return view(
            'livewire.vendor.templates',
            compact('templates')
        )->layout('layouts.vendor');
    }
}
