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
        $table->string('quality_rating')
            ->nullable()
            ->after('verified_name');

        $table->string('verification_status')
            ->nullable()
            ->after('quality_rating');

        $table->timestamp('last_verified_at')
            ->nullable()
            ->after('connected_at');

        $table->text('last_error')
            ->nullable()
            ->after('last_verified_at');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->dropColumn('quality_rating');
            $table->dropColumn('verification_status');
            $table->dropColumn('last_verified_at');
            $table->dropColumn('last_error');
        });
    }
};
