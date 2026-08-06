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
        Schema::create('media_libraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('original_name');

            $table->string('disk')->default('public');

            $table->string('path');

            $table->string('mime_type');

            $table->unsignedBigInteger('size');

            $table->string('type');

            $table->string('meta_media_id')->nullable();

            $table->boolean('uploaded_to_meta')
                ->default(false);

            $table->timestamp('uploaded_at')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_libraries');
    }
};
