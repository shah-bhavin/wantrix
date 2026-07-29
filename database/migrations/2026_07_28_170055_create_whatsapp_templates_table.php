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
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('meta_template_id')->nullable();

            $table->string('name');

            $table->string('language');

            $table->string('category');

            $table->string('status');

            $table->string('quality_score')->nullable();

            $table->boolean('allow_category_change')
                ->default(false);

            $table->json('components')->nullable();

            $table->json('raw_response')->nullable();

            $table->timestamp('last_synced_at')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};
