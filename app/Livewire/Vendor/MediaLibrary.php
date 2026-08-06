<?php

namespace App\Livewire\Vendor;

use App\Models\MediaLibrary as MediaModel;
use App\Services\Whatsapp\MediaUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;

class MediaLibrary extends Component
{
    use WithFileUploads;

    public $file;

    public string $search = '';


    public function uploadMedia(MediaUploadService $service)
    {
        $this->validate([
            'file' => 'required|max:51200',
        ]);
        if (!$this->file) {

            session()->flash(
                'error',
                'Please select a file.'
            );

            return;
        }

        $account = auth()
            ->user()
            ->vendor
            ->whatsappAccounts()
            ->where('is_active', true)
            ->first();

        if (!$account) {

            session()->flash(
                'error',
                'No active WhatsApp account found.'
            );

            return;
        }

        $service->upload(
            $account,
            $this->file
        );

        $this->reset('file');

        session()->flash(
            'success',
            'Media uploaded successfully.'
        );
    }

    public function delete($id)
    {
        MediaModel::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Media deleted.'
        );
    }

    public function render()
    {
        logger('MediaLibrary render');

        $media = auth()
            ->user()
            ->vendor
            ->mediaLibraries()
            ->when($this->search, function ($query) {

                $query->where(function ($query) {

                    $query
                        ->where(
                            'original_name',
                            'like',
                            "%{$this->search}%"
                        )
                        ->orWhere(
                            'mime_type',
                            'like',
                            "%{$this->search}%"
                        );
                });
            })
            ->latest()
            ->get();

        return view(
            'livewire.vendor.media-library',
            compact('media')
        )->layout('layouts.vendor');
    }
}
