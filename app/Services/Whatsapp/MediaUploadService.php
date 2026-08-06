<?php

namespace App\Services\Whatsapp;

use App\Enums\MediaType;
use App\Models\MediaLibrary;
use App\Models\WhatsappAccount;
use App\Services\Meta\MetaApiService;
use Illuminate\Http\UploadedFile;

class MediaUploadService
{
    public function __construct(

        protected MetaApiService $meta,

    ) {}

    public function upload(

        WhatsappAccount $account,

        UploadedFile $file,

    ): MediaLibrary {

        $type = $this->detectType($file);

        $path = $file->store(

            "media/vendor_{$account->vendor_id}",

            'public'

        );

        $media = MediaLibrary::create([

            'vendor_id' => $account->vendor_id,

            'name' => pathinfo(

                $file->getClientOriginalName(),

                PATHINFO_FILENAME

            ),

            'original_name' =>

            $file->getClientOriginalName(),

            'disk' => 'public',

            'path' => $path,

            'mime_type' => $file->getMimeType(),

            'size' => $file->getSize(),

            'type' => $type,

        ]);

        $result = $this->meta->uploadMedia(

            phoneNumberId: $account->phone_number_id,

            accessToken: $account->access_token,

            path: storage_path(

                "app/public/{$media->path}"

            ),

            mimeType: $media->mime_type,

        );

        if ($result['success']) {

            $media->update([

                'meta_media_id'     => $result['data']['id'],

                'uploaded_to_meta'  => true,

                'uploaded_at'       => now(),

            ]);
        }

        return $media->fresh();
    }

    protected function detectType(
        UploadedFile $file,
    ): MediaType {

        $mime = $file->getMimeType();

        if (str_starts_with($mime, 'image/')) {

            return MediaType::IMAGE;
        }

        if (str_starts_with($mime, 'video/')) {

            return MediaType::VIDEO;
        }

        if (str_starts_with($mime, 'audio/')) {

            return MediaType::AUDIO;
        }

        if (
            str_contains($mime, 'pdf')
            || str_contains($mime, 'document')
        ) {

            return MediaType::DOCUMENT;
        }

        return MediaType::UNKNOWN;
    }
}
