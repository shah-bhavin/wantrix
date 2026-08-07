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
         Schema::table('incoming_messages', function (Blueprint $table) {
            $table->unique(
                ['whatsapp_account_id', 'provider_message_id'],
                'incoming_messages_account_provider_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming_messages', function (Blueprint $table) {
            $table->dropUnique(
                'incoming_messages_account_provider_unique'
            );
        });
    }
};
