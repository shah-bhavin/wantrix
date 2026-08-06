<?php

namespace App\Services\Whatsapp\Providers;

use App\Models\MediaLibrary;
use App\Models\Message;
use App\Models\WhatsappAccount;
use App\Services\Whatsapp\Contracts\WhatsappProvider;
use Illuminate\Support\Str;

class DummyProvider implements WhatsappProvider
{
    public function send(Message $message): array
    {
        if ($message->id % 3 === 0) {

            return [

                'success' => false,

                'error' => 'Simulated provider failure.'

            ];
        }

        return [

            'success' => true,

            'message_id' => fake()->uuid()

        ];
    }

    public function uploadMedia(
        WhatsappAccount $account,
        MediaLibrary $media
    ): array;

    public function verifyConnection(array $credentials): array
    {
        return [
            'success' => true
        ];
    }

    public function subscribeWebhook(array $credentials): array
    {
        return [
            'success' => true
        ];
    }

    public function uploadMedia(
        WhatsappAccount $account,
        MediaLibrary $media
    ): array {

        // simulate upload delay
        usleep(500000);

        $metaMediaId = 'dummy_' . Str::uuid();

        $media->update([
            'uploaded_to_meta' => true,
            'meta_media_id'     => $metaMediaId,
            'meta_uploaded_at'  => now(),
        ]);

        return [
            'success' => true,
            'media_id' => $metaMediaId,
        ];
    }
}
