<?php

namespace App\Services\Meta;

use App\Models\Vendor;
use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Http;

class MetaTemplateService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('meta.graph_url'),
            '/'
        ) . '/' . config('meta.graph_version');
    }

    /**
     * Sync all templates for a vendor.
     */
    public function sync(Vendor $vendor): array
    {
        $account = $vendor
            ->whatsappAccounts()
            ->where('is_active', true)
            ->first();

        if (! $account) {

            throw new \Exception(
                'No active WhatsApp account found.'
            );
        }

        $response = Http::withToken(
            $account->access_token
        )
            ->acceptJson()
            ->get(
                "{$this->baseUrl}/{$account->phone_number_id}/message_templates"
            );
    }
}
