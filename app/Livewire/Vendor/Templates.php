<?php

namespace App\Livewire\Vendor;

use App\Models\Template;
use Livewire\Component;

class Templates extends Component
{
    public bool $showCreateModal = false;
    public string $name = '';
    public string $category = 'marketing';
    public string $body = '';

    public function createTemplate(): void
    {
        $this->validate([
            'name' => ['required'],
            'category' => ['required'],
            'body' => ['required'],
        ]);

        Template::create([
            'vendor_id' => auth()->user()->vendor_id,
            'name' => $this->name,
            'category' => $this->category,
            'body' => $this->body,
        ]);

        $this->reset(['name', 'body']);
        $this->category = 'marketing';
        $this->showCreateModal = false;

        session()->flash('success', 'Template created successfully.');

    }

    public function render()
    {
        $templates = Template::query()
            ->where('vendor_id', auth()->user()->vendor_id)
            ->latest()
            ->get();

        return view('livewire.vendor.templates', compact('templates'))->layout('layouts.vendor');
    }
}
