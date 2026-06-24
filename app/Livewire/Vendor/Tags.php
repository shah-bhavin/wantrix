<?php

namespace App\Livewire\Vendor;
use App\Models\Tag;
use Livewire\Component;

class Tags extends Component
{
    public bool $showCreateModal = false;
    public string $name = '';
    public string $color = 'amber';

    protected function rules(): array
    {
        return [
            'name' => ['required'],
            'color' => ['required'],
        ];
    }

    public function createTag(): void
    {
        $data = $this->validate();

        Tag::create([
            'vendor_id' => auth()->user()->vendor_id,
            'name' => $data['name'],
            'color' => $data['color'],
        ]);

        $this->reset(['name']);
        $this->color = 'amber';
        $this->showCreateModal = false;

        session()->flash('success', 'Tag created successfully.');
    }

    public function render()
    {
        $tags = Tag::query()
            ->where('vendor_id', auth()->user()->vendor_id)
            ->latest()
            ->get();

        return view('livewire.vendor.tags', compact('tags'))->layout('layouts.vendor');
    }

}
