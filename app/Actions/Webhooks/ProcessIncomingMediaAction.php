<?php

namespace App\Actions\Webhooks;

use App\Models\IncomingMessage;

class ProcessIncomingMediaAction
{
    public function execute(
        IncomingMessage $incomingMessage,
        array $message
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Will be implemented when real Meta account is available
        |--------------------------------------------------------------------------
        */

        /*
            image.id
            document.id
            video.id
            audio.id

            ↓

            Meta Media API

            ↓

            Download

            ↓

            Media Library

            ↓

            incoming_message.media_library_id
        */

    }
}