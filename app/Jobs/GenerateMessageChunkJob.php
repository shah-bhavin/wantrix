<?php

namespace App\Jobs;

use App\Enums\MessageStatus;
use App\Enums\MessageType;
use App\Models\Campaign;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateMessageChunkJob implements ShouldQueue
{
    use Batchable, Queueable;

    public function __construct(
        public Campaign $campaign,
        public array $contactIds,
    ) {
        $this->onQueue('campaign-generation');
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $contacts = $this->campaign
            ->group
            ->contacts()
            ->whereIn('contacts.id', $this->contactIds)
            ->get();

        foreach ($contacts as $contact) {
            $message = $this->campaign
                ->messages()
                ->create([

                    'vendor_id'            => $this->campaign->vendor_id,

                    'contact_id'           => $contact->id,

                    'whatsapp_account_id'  => $this->campaign->whatsapp_account_id,

                    'whatsapp_template_id' => $this->campaign->template_id,

                    'media_library_id'     => $this->campaign->media_library_id,

                    'type'                 => $this->campaign->message_type,

                    'body'                 => $this->resolveBody(),

                    'status'               => MessageStatus::PENDING,

                ]);

            $message->statusHistories()->create([
                'status' => MessageStatus::PENDING,
                'changed_at' => now(),
            ]);
        }
    }

    protected function resolveBody(): ?string
    {
        return match ($this->campaign->message_type) {

            MessageType::TEXT =>
            $this->campaign->template?->body,

            MessageType::BUTTONS =>
            $this->campaign->template?->body,

            MessageType::TEMPLATE =>
            null,

            MessageType::IMAGE,
            MessageType::VIDEO,
            MessageType::DOCUMENT,
            MessageType::AUDIO =>
            null,

            default => '',
        };
    }
}
