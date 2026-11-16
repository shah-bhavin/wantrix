<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappAccount;
use App\Models\WhatsappTemplate;
use App\Services\Meta\MetaApiService;

class TemplateSyncService
{
    public function __construct(
        protected MetaApiService $meta
    ) {}

    public function sync(WhatsappAccount $account): array
    {
        $templates = $this->meta->getTemplates(
            $account->waba_id,
            $account->access_token
        );

        if (!($templates['success'] ?? false)) {
            return $templates;
        }

        $syncedCount = 0;

        foreach ($templates['data']['data'] ?? [] as $template) {

            if (empty($template['id'])) {
                continue;
            }

            WhatsappTemplate::updateOrCreate(
                [
                    'vendor_id' => $account->vendor_id,
                    'whatsapp_account_id' => $account->id,
                    'meta_template_id' => $template['id'],
                ],
                [
                    'name' => $template['name'] ?? '',
                    'language' => $template['language'] ?? '',
                    'category' => $template['category'] ?? '',
                    'status' => $template['status'] ?? 'PENDING',
                    'quality_score' => $template['quality_score']['score'] ?? null,
                    'allow_category_change' => $template['allow_category_change'] ?? false,
                    'components' => $template['components'] ?? [],
                    'raw_response' => $template,
                    'last_synced_at' => now(),
                ]
            );

            $syncedCount++;
        }

        return [
            'success' => true,
            'count' => $syncedCount,
        ];
    }
}