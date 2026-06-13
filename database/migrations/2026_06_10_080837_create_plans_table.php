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
        Schema::create('plans', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->integer('trial_days')->default(0);
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->decimal('yearly_price', 10, 2)->default(0);

            $table->integer('max_users')->default(1);

            $table->integer('max_contacts')->default(0);

            $table->integer('max_whatsapp_numbers')->default(0);

            $table->integer('max_campaigns_per_month')->default(0);

            $table->boolean('is_unlimited_users')->default(false);

            $table->boolean('is_unlimited_contacts')->default(false);

            $table->boolean('is_unlimited_whatsapp_numbers')->default(false);

            $table->boolean('is_unlimited_campaigns')->default(false);

            $table->boolean('is_popular')->default(false);

            $table->string('status')
                ->default('active');

            $table->integer('sort_order')
                ->default(0);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
