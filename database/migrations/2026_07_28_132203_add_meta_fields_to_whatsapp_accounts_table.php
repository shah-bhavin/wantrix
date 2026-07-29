<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->string('display_phone_number')->nullable()->after('phone_number_id');

            $table->string('verified_name')->nullable()->after('display_phone_number');

            $table->string('webhook_verify_token')->nullable()->after('access_token');

            $table->json('metadata')->nullable()->after('webhook_verify_token');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->dropColumn('display_phone_number');
            $table->dropColumn('verified_name');
            $table->dropColumn('webhook_verify_token');
            $table->dropColumn('metadata');

        });
    }
};
