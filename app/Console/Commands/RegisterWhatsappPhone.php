<?php

namespace App\Console\Commands;

use App\Models\WhatsappAccount;
use App\Services\Meta\MetaApiService;
use Illuminate\Console\Command;

class RegisterWhatsappPhone extends Command
{
    protected $signature = 'whatsapp:register-phone
                            {account_id : WhatsApp account ID}
                            {pin : 6-digit registration PIN}';

    protected $description = 'Register a WhatsApp phone number with Meta Cloud API';

    public function handle(MetaApiService $meta): int
    {
        $accountId = $this->argument('account_id');
        $pin = $this->argument('pin');

        if (! preg_match('/^\d{6}$/', $pin)) {
            $this->error('PIN must be exactly 6 digits.');

            return self::FAILURE;
        }

        $account = WhatsappAccount::find($accountId);

        if (! $account) {
            $this->error(
                "WhatsApp account {$accountId} was not found."
            );

            return self::FAILURE;
        }

        if (blank($account->phone_number_id)) {
            $this->error(
                'This account does not have a phone_number_id.'
            );

            return self::FAILURE;
        }

        if (blank($account->access_token)) {
            $this->error(
                'This account does not have an access token.'
            );

            return self::FAILURE;
        }

        $this->info('Registering WhatsApp phone number...');
        $this->line(
            "Account ID: {$account->id}"
        );
        $this->line(
            "Phone Number ID: {$account->phone_number_id}"
        );
        $this->newLine();

        $result = $meta->registerPhone(
            $account->phone_number_id,
            $account->access_token,
            $pin
        );

        if (! ($result['success'] ?? false)) {

            $this->error('Meta registration failed.');

            $this->line(
                json_encode(
                    $result,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            return self::FAILURE;
        }

        $this->info(
            'Meta phone registration successful!'
        );

        $this->line(
            json_encode(
                $result,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return self::SUCCESS;
    }
}