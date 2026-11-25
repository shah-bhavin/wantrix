<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappAccount;
use App\Models\WhatsappTemplate;
use App\Services\Meta\MetaApiService;
use App\Services\Meta\MetaTokenService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TemplateSyncService
{
    public function __construct(
        protected MetaApiService $meta,
        protected MetaTokenService $tokens
    ) {}

    public function sync(WhatsappAccount $account): array
    {
        $account->refresh();

        if (blank($account->waba_id)) {
            return [
                'success' => false,
                'error' => 'WhatsApp Business Account ID is missing.',
            ];
        }

        if (! $account->is_active) {
            return [
                'success' => false,
                'error' => 'This WhatsApp account is inactive.',
            ];
        }

        try {
            $accessToken = $this->tokens->getAccessToken($account);

            if (blank($accessToken)) {
                return [
                    'success' => false,
                    'error' => 'WhatsApp access token is missing.',
                ];
            }

            $result = $this->meta->getTemplates(
                $account->waba_id,
                $accessToken
            );

            if (! ($result['success'] ?? false)) {
                return [
                    'success' => false,
                    'error' => $result['error']
                        ?? 'Unable to fetch WhatsApp templates from Meta.',
                    'details' => $result['details'] ?? null,
                ];
            }

            $templates = data_get($result, 'data.data', []);

            if (! is_array($templates)) {
                return [
                    'success' => false,
                    'error' => 'Meta returned an invalid template response.',
                ];
            }

            $syncedCount = 0;
            $skippedCount = 0;

            DB::transaction(function () use (
                $account,
                $templates,
                &$syncedCount,
                &$skippedCount
            ) {
                foreach ($templates as $template) {
                    $metaTemplateId = data_get($template, 'id');
                    $name = data_get($template, 'name');

                    if (blank($metaTemplateId) || blank($name)) {
                        $skippedCount++;
                        continue;
                    }

                    $record = WhatsappTemplate::withTrashed()->firstOrNew([
                        'vendor_id' => $account->vendor_id,
                        'whatsapp_account_id' => $account->id,
                        'meta_template_id' => (string) $metaTemplateId,
                    ]);

                    if ($record->exists && $record->trashed()) {
                        $record->restore();
                    }

                    $record->fill([
                        'name' => (string) $name,
                        'language' => data_get($template, 'language', 'en'),
                        'category' => data_get($template, 'category'),
                        'status' => strtoupper(
                            (string) data_get($template, 'status', 'PENDING')
                        ),
                        'quality_score' => data_get(
                            $template,
                            'quality_score.score'
                        ),
                        'allow_category_change' => (bool) data_get(
                            $template,
                            'allow_category_change',
                            false
                        ),
                        'components' => data_get($template, 'components', []),
                        'raw_response' => $template,
                        'last_synced_at' => now(),
                    ]);

                    $record->save();

                    $syncedCount++;
                }
            });

            return [
                'success' => true,
                'count' => $syncedCount,
                'skipped' => $skippedCount,
                'message' => "{$syncedCount} WhatsApp templates synced successfully.",
            ];

        } catch (Throwable $exception) {
            Log::error('WhatsApp template synchronization failed.', [
                'account_id' => $account->id,
                'vendor_id' => $account->vendor_id,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            report($exception);

            return [
                'success' => false,
                'error' => 'Template synchronization failed. Please check the application logs.',
            ];
        }
    }
}
