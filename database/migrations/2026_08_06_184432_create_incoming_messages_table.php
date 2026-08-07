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
        Schema::create('incoming_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('contact_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('whatsapp_account_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('media_library_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->string('provider_message_id')->unique();

            $table->string('from_phone');

            $table->text('body')->nullable();

            $table->enum('type', [

                'text',

                'image',

                'document',

                'video',

                'audio',

                'voice',

                'sticker',

                'location',

                'contact',

                'button',

                'interactive',

                'reaction',

                'unknown',

            ]);

            $table->json('payload')->nullable();
            $table->timestamp('received_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_messages');
    }
};
