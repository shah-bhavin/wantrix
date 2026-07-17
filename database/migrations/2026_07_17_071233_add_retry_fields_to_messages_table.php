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
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedTinyInteger('retry_count')
                ->default(0)
                ->after('failure_reason');

            $table->timestamp('last_retried_at')
                ->nullable()
                ->after('retry_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn([
                'retry_count',
                'last_retried_at',
            ]);
        });
    }
};
