<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CampaignMessageUniqueIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_same_contact_cannot_receive_duplicate_message_in_same_campaign(): void
    {
        [$vendorId, $groupId, $contactId] = $this->createBaseRecords();

        $campaignId = $this->createCampaign($vendorId, $groupId);

        $this->createMessage($vendorId, $campaignId, $contactId);

        $this->expectException(QueryException::class);

        $this->createMessage($vendorId, $campaignId, $contactId);
    }

    public function test_same_contact_can_receive_messages_in_different_campaigns(): void
    {
        [$vendorId, $groupId, $contactId] = $this->createBaseRecords();

        $firstCampaign = $this->createCampaign($vendorId, $groupId);
        $secondCampaign = $this->createCampaign($vendorId, $groupId);

        $this->createMessage($vendorId, $firstCampaign, $contactId);
        $this->createMessage($vendorId, $secondCampaign, $contactId);

        $this->assertDatabaseCount('messages', 2);
    }

    public function test_direct_messages_with_null_campaign_id_can_be_multiple(): void
    {
        [$vendorId, , $contactId] = $this->createBaseRecords();

        $this->createMessage($vendorId, null, $contactId);
        $this->createMessage($vendorId, null, $contactId);

        $this->assertDatabaseCount('messages', 2);
    }

    private function createBaseRecords(): array
    {
        $now = now();

        $vendorId = DB::table('vendors')->insertGetId([
            'name' => 'Test Vendor',
            'slug' => 'test-vendor-' . uniqid(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $groupId = DB::table('groups')->insertGetId([
            'vendor_id' => $vendorId,
            'name' => 'Test Group',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $contactId = DB::table('contacts')->insertGetId([
            'vendor_id' => $vendorId,
            'name' => 'Test Contact',
            'phone_number' => '919999' . random_int(100000, 999999),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('contact_group')->insert([
            'contact_id' => $contactId,
            'group_id' => $groupId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return [$vendorId, $groupId, $contactId];
    }

    private function createCampaign(int $vendorId, int $groupId): int
    {
        $now = now();

        return DB::table('campaigns')->insertGetId([
            'vendor_id' => $vendorId,
            'group_id' => $groupId,
            'name' => 'Test Campaign',
            'status' => 'draft',
            'message_type' => 'text',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function createMessage(
        int $vendorId,
        ?int $campaignId,
        int $contactId
    ): void {
        $now = now();

        DB::table('messages')->insert([
            'vendor_id' => $vendorId,
            'campaign_id' => $campaignId,
            'contact_id' => $contactId,
            'body' => 'Test message',
            'type' => 'text',
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
