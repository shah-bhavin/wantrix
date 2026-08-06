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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('message_type')
                ->default('template')
                ->after('template_id');

            $table->foreignId('media_library_id')
                ->nullable()
                ->after('message_type')
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropConstrainedForeignId('media_library_id');

            $table->dropColumn('message_type');
        });
    }
};
