<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithFileUploads;

class CompanySettings extends Component
{
    use WithFileUploads;
    public $logo;
    public string $name = '';
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $website = null;
    public ?string $address = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $country = null;
    public ?string $postal_code = null;
    public ?string $gst_number = null;
    public string $timezone = 'Asia/Kolkata';
    

    public function mount(): void
    {
        $vendor = auth()->user()->vendor;
        
        $this->fill(
            $vendor->only([
                'name',
                'email',
                'phone',
                'website',
                'address',
                'city',
                'state',
                'country',
                'postal_code',
                'gst_number',
                'timezone',
            ])
        );
    }
    
    protected function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable'],
            'website' => ['nullable'],
            'gst_number' => ['nullable'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }


    public function save(): void
    {
        $vendor = auth()->user()->vendor;
        $data = $this->validate();
        
        if ($this->logo) {
            $path = $this->logo->store('vendor-logos', 'public');
            $vendor->logo = $path;
        }

        $vendor->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'gst_number' => $this->gst_number,
            'timezone' => $this->timezone,
        ]);

        session()->flash('success', 'Company settings updated.');
    }


    public function render()
    {
        return view('livewire.vendor.company-settings')->layout('layouts.vendor');
    }
}
