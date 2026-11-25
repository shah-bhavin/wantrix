<?php

namespace Tests\Feature;

use App\Actions\Campaigns\CancelCampaignAction;
use App\Actions\Campaigns\PauseCampaignAction;
use App\Actions\Campaigns\ResumeCampaignAction;
use App\Enums\CampaignStatus;
use App\Enums\MessageStatus;
use App\Jobs\ProcessMessageJob;
use App\Models\Campaign;
use App\Models\Message;
use App\Services\CampaignWorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CampaignLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_processing_campaign_can_be_paused(): void
    {
        $campaign = $this->createCampaign('processing');

        $result = app(PauseCampaignAction::class)->execute($campaign);

        $this->assertTrue($result);
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'status' => 'paused',
        ]);
    }

    public function test_paused_campaign_can_be_resumed_and_pending_messages_are_queued(): void
    {
        Queue::fake();

        $campaign = $this->createCampaign('paused');

        $pending = $this->createMessage($campaign, 'pending');
        $queued = $this->createMessage($campaign, 'queued');

        $result = app(ResumeCampaignAction::class)->execute($campaign);

        $this->assertTrue($result);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'status' => 'processing',
        ]);

        $this->assertDatabaseHas('messages', [
            'id' => $pending->id,
            'status' => 'queued',
        ]);

        $this->assertDatabaseHas('messages', [
            'id' => $queued->id,
            'status' => 'queued',
        ]);

        $this->assertSame(
            2,
            Queue::pushed(ProcessMessageJob::class)
                ->where('queue', 'messages')
                ->count()
        );
    }

    public function test_processing_campaign_can_be_cancelled_and_pending_messages_fail(): void
    {
        $campaign = $this->createCampaign('processing');

        $pending = $this->createMessage($campaign, 'pending');
        $queued = $this->createMessage($campaign, 'queued');

        $result = app(CancelCampaignAction::class)->execute($campaign);

        $this->assertTrue($result);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('messages', [
            'id' => $pending->id,
            'status' => 'failed',
        ]);

        $this->assertDatabaseHas('messages', [
            'id' => $queued->id,
            'status' => 'failed',
        ]);
    }

    public function test_failed_messages_can_be_retried_and_campaign_reopens(): void
    {
        Queue::fake();

        $campaign = $this->createCampaign('completed');

        $message = $this->createMessage($campaign, 'failed');

        $count = app(CampaignWorkflowService::class)
            ->retryFailedMessages($campaign, [$message->id]);

        $this->assertSame(1, $count);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'status' => 'processing',
        ]);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'status' => 'queued',
            'retry_count' => 1,
        ]);

        Queue::assertPushedOn('messages', ProcessMessageJob::class);
    }

    public function test_failed_message_at_retry_limit_is_not_retried(): void
    {
        Queue::fake();

        $campaign = $this->createCampaign('completed');

        $message = $this->createMessage(
            $campaign,
            'failed',
            3
        );

        $count = app(CampaignWorkflowService::class)
            ->retryFailedMessages($campaign, [$message->id]);

        $this->assertSame(0, $count);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'status' => 'failed',
            'retry_count' => 3,
        ]);

        Queue::assertNothingPushed();
    }

    public function test_processing_campaign_completes_when_all_messages_are_final(): void
    {
        $campaign = $this->createCampaign('processing');

        $this->createMessage($campaign, 'sent');
        $this->createMessage($campaign, 'failed');

        app(CampaignWorkflowService::class)->complete($campaign);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'status' => 'completed',
        ]);
    }

    private function createCampaign(string $status): Campaign
    {
        $now = now();

        $vendorId = DB::table('vendors')->insertGetId([
            'name' => 'Lifecycle Vendor',
            'slug' => 'lifecycle-' . uniqid(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $groupId = DB::table('groups')->insertGetId([
            'vendor_id' => $vendorId,
            'name' => 'Lifecycle Group',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $campaignId = DB::table('campaigns')->insertGetId([
            'vendor_id' => $vendorId,
            'group_id' => $groupId,
            'template_id' => null,
            'name' => 'Lifecycle Campaign',
            'status' => $status,
            'message_type' => 'text',
            'message_body' => 'Test campaign message',
            'message_delay_seconds' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return Campaign::findOrFail($campaignId);
    }

    private function createMessage(
        Campaign $campaign,
        string $status,
        int $retryCount = 0
    ): Message {
        $now = now();

        $contactId = DB::table('contacts')->insertGetId([
            'vendor_id' => $campaign->vendor_id,
            'name' => 'Lifecycle Contact',
            'phone_number' => '9199' . random_int(10000000, 99999999),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $messageId = DB::table('messages')->insertGetId([
            'vendor_id' => $campaign->vendor_id,
            'campaign_id' => $campaign->id,
            'contact_id' => $contactId,
            'direction' => 'outgoing',
            'body' => 'Test message',
            'type' => 'text',
            'status' => $status,
            'retry_count' => $retryCount,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return Message::findOrFail($messageId);
    }
}
