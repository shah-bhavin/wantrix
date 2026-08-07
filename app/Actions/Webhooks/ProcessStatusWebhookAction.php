<?php

namespace App\Actions\Webhooks;

use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\MessageStatusService;

class ProcessStatusWebhookAction
{
    public function __construct(
        protected MessageStatusService $statusService,
    ) {}

    public function execute(array $status): void
    {
        $providerMessageId = $status['id'] ?? null;

        if (! $providerMessageId) {
            return;
        }

        $message = Message::where(
            'provider_message_id',
            $providerMessageId
        )->first();

        if (! $message) {
            return;
        }

        $mappedStatus = match ($status['status']) {

            'sent'       => MessageStatus::SENT,

            'delivered'  => MessageStatus::DELIVERED,

            'read'       => MessageStatus::READ,

            'failed'     => MessageStatus::FAILED,

            default      => null,
        };

        if (! $mappedStatus) {
            return;
        }

        $attributes = [];

        switch ($mappedStatus) {

            case MessageStatus::SENT:

                $attributes['sent_at'] = now();

                break;

            case MessageStatus::DELIVERED:

                $attributes['delivered_at'] = now();

                break;

            case MessageStatus::READ:

                $attributes['read_at'] = now();

                break;

            case MessageStatus::FAILED:

                $attributes['failure_reason'] =
                    $status['errors'][0]['title']
                    ?? 'Meta returned failure.';

                break;
        }

        $this->statusService->change(
            $message,
            $mappedStatus,
            $attributes
        );
    }
}
