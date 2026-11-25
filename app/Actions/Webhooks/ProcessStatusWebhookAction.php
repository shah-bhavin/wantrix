<?php

namespace App\Actions\Webhooks;

use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\MessageStatusService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessStatusWebhookAction
{
    public function __construct(
        protected MessageStatusService $statusService,
    ) {}

    public function execute(array $status): void
    {
        $providerMessageId = $status['id'] ?? null;
        $providerStatus = strtolower((string) ($status['status'] ?? ''));

        if (blank($providerMessageId) || blank($providerStatus)) {
            return;
        }

        $message = Message::query()
            ->where('provider_message_id', $providerMessageId)
            ->first();

        if (! $message) {
            Log::warning('WhatsApp webhook status has no matching message.', [
                'provider_message_id' => $providerMessageId,
                'provider_status' => $providerStatus,
            ]);

            return;
        }

        $mappedStatus = match ($providerStatus) {
            'sent' => MessageStatus::SENT,
            'delivered' => MessageStatus::DELIVERED,
            'read' => MessageStatus::READ,
            'failed' => MessageStatus::FAILED,
            default => null,
        };

        if (! $mappedStatus) {
            return;
        }

        $eventTime = $this->eventTimestamp($status['timestamp'] ?? null);
        $attributes = [];

        switch ($mappedStatus) {
            case MessageStatus::SENT:
                $attributes['sent_at'] = $eventTime;
                break;

            case MessageStatus::DELIVERED:
                $attributes['delivered_at'] = $eventTime;
                break;

            case MessageStatus::READ:
                $attributes['read_at'] = $eventTime;
                break;

            case MessageStatus::FAILED:
                $attributes['failure_reason'] = $this->failureReason($status);
                break;
        }

        $changed = $this->statusService->change(
            $message,
            $mappedStatus,
            $attributes
        );

        if (! $changed) {
            Log::info('WhatsApp webhook status transition was ignored.', [
                'message_id' => $message->id,
                'current_status' => $message->fresh()?->status?->value,
                'incoming_status' => $providerStatus,
            ]);

            return;
        }

        if ($mappedStatus === MessageStatus::FAILED) {
            Log::warning('WhatsApp message delivery failed.', [
                'message_id' => $message->id,
                'provider_message_id' => $providerMessageId,
                'recipient_id' => $status['recipient_id'] ?? null,
                'errors' => collect($status['errors'] ?? [])
                    ->map(fn ($error) => [
                        'code' => $error['code'] ?? null,
                        'title' => $error['title'] ?? null,
                        'message' => $error['message'] ?? null,
                        'details' => data_get($error, 'error_data.details'),
                    ])
                    ->all(),
            ]);
        }
    }

    protected function eventTimestamp(mixed $timestamp): Carbon
    {
        if (is_numeric($timestamp) && (int) $timestamp > 0) {
            return Carbon::createFromTimestamp((int) $timestamp);
        }

        return now();
    }

    protected function failureReason(array $status): string
    {
        $errors = collect($status['errors'] ?? [])
            ->map(function ($error) {
                return collect([
                    $error['code'] ?? null,
                    $error['title'] ?? null,
                    $error['message'] ?? null,
                    data_get($error, 'error_data.details'),
                ])
                    ->filter(fn ($value) => filled($value))
                    ->implode(' - ');
            })
            ->filter()
            ->implode('; ');

        return mb_substr(
            $errors !== '' ? $errors : 'Meta returned a failed message status.',
            0,
            2000
        );
    }
}
