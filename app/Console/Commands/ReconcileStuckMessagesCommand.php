<?php

namespace App\Console\Commands;

use App\Enums\MessageStatus;
use App\Models\Message;
use App\Services\MessageStatusService;
use Illuminate\Console\Command;

class ReconcileStuckMessagesCommand extends Command
{
    protected $signature = 'app:reconcile-stuck-messages
                            {--minutes=10 : Minimum minutes a message must remain in sending status}';

    protected $description = 'Find and reconcile messages stuck in sending status';

    public function handle(MessageStatusService $statusService): int
    {
        $minutes = max(1, (int) $this->option('minutes'));
        $cutoff = now()->subMinutes($minutes);

        $messages = Message::query()
            ->where('status', MessageStatus::SENDING)
            ->where('updated_at', '<=', $cutoff)
            ->orderBy('id')
            ->get();

        if ($messages->isEmpty()) {
            $this->info('No stuck messages found.');

            return self::SUCCESS;
        }

        $reconciled = 0;
        $needsReview = 0;

        foreach ($messages as $message) {
            if (filled($message->provider_message_id)) {
                $changed = $statusService->change(
                    $message,
                    MessageStatus::SENT,
                    [
                        'failure_reason' => null,
                    ]
                );

                if ($changed) {
                    $reconciled++;
                    continue;
                }
            }

            $needsReview++;
        }

        $this->newLine();

        $this->table(
            ['Message ID', 'Campaign ID', 'Provider ID', 'Updated At', 'Action'],
            $messages->map(function ($message) {
                return [
                    $message->id,
                    $message->campaign_id ?? 'Direct',
                    filled($message->provider_message_id) ? 'Available' : 'Missing',
                    $message->updated_at?->toDateTimeString(),
                    filled($message->provider_message_id)
                        ? 'Reconcile to SENT'
                        : 'Manual review - do not resend',
                ];
            })->all()
        );

        $this->newLine();
        $this->info("Reconciled to SENT: {$reconciled}");

        if ($needsReview > 0) {
            $this->warn("Needs manual review: {$needsReview}");
            $this->warn('These messages were NOT resent or automatically marked failed.');
        }

        return self::SUCCESS;
    }
}
