<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Contact;

class ImportContacts extends Component
{
    use WithFileUploads;
    public $csv;
    public int $importedCount = 0;
    public int $skippedCount = 0;
    
    public array $previewRows = [];

    protected $rules = [
        'csv' => [
            'required',
            'file',
            'mimes:csv,txt',
        ],
    ];

    public function updatedCsv(): void
    {
        $this->previewRows = [];

        if (!$this->csv) {
            return;
        }

        $handle = fopen($this->csv->getRealPath(), 'r');
        $rowNumber = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if ($rowNumber >= 10) {
                break;
            }

            $this->previewRows[] = $row;
            $rowNumber++;
        }

        fclose($handle);
    }


    public function render()
    {
        return view('livewire.vendor.import-contacts')->layout('layouts.vendor');
    }

    public function importContacts(): void
    {
        $this->importedCount = 0;
        $this->skippedCount = 0;

        $handle = fopen($this->csv->getRealPath(), 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $name = trim($row[0] ?? '');
            $phone = trim($row[1] ?? '');
            $email = trim($row[2] ?? '');
            $company = trim($row[3] ?? '');

            if (!$name || !$phone) {
                $this->skippedCount++;
                continue;
            }

            $exists = Contact::query()
                ->where('vendor_id', auth()->user()->vendor_id)
                ->where('phone_number', $phone)
                ->exists();

            if ($exists) {
                $this->skippedCount++;
                continue;
            }

            Contact::create([
                'vendor_id' => auth()->user()->vendor_id,
                'name' => $name,
                'phone_number' => $phone,
                'email' => $email,
                'company' => $company,
                'status' => 'active',
            ]);

            $this->importedCount++;
        }

        fclose($handle);

        session()->flash('success', "{$this->importedCount} contacts imported. {$this->skippedCount} skipped.");
    }

}
