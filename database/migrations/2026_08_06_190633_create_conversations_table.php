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
        Schema::create('conversations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();

            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();

            $table->foreignId('whatsapp_account_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamp('last_message_at')->nullable();

            $table->timestamp('last_incoming_at')->nullable();

            $table->timestamp('last_outgoing_at')->nullable();

            $table->boolean('is_closed')
                ->default(false);

            $table->boolean('is_ai_enabled')
                ->default(false);

            $table->unsignedInteger('unread_count')
                ->default(0);

            $table->timestamps();

            $table->unique([
                'vendor_id',
                'contact_id',
                'whatsapp_account_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
