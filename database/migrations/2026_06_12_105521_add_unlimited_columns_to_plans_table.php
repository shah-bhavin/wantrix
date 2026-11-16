
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('is_unlimited_users')
                ->default(false)
                ->after('max_users');

            $table->boolean('is_unlimited_contacts')
                ->default(false)
                ->after('max_contacts');

            $table->boolean('is_unlimited_whatsapp_numbers')
                ->default(false)
                ->after('max_whatsapp_numbers');

            $table->boolean('is_unlimited_campaigns')
                ->default(false)
                ->after('max_campaigns_per_month');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'is_unlimited_users',
                'is_unlimited_contacts',
                'is_unlimited_whatsapp_numbers',
                'is_unlimited_campaigns',
            ]);
        });
    }
};
