<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Local template is no longer mandatory for every campaign.
            $table->dropForeign(['template_id']);
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreignId('template_id')
                ->nullable()
                ->change();

            $table->foreignId('whatsapp_template_id')
                ->nullable()
                ->after('template_id')
                ->constrained('whatsapp_templates')
                ->nullOnDelete();

            $table->longText('message_body')
                ->nullable()
                ->after('message_type');
        });

        // Restore the original local-template foreign key with nullable support.
        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreign('template_id')
                ->references('id')
                ->on('templates')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['whatsapp_template_id']);
            $table->dropColumn(['whatsapp_template_id', 'message_body']);
        });

        // Keep template_id nullable during rollback to avoid breaking campaigns
        // that may have been created without a local template.
    }
};