<?php

declare(strict_types=1);

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
        Schema::create('seo_routes', function (Blueprint $table): void {
            $table->id();
            $table->string('route_name', 255)->unique();

            // Standard Meta
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('keywords', 500)->nullable();
            $table->string('canonical', 500)->nullable();
            $table->string('robots', 50)->nullable();

            // Open Graph Meta
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('og_type', 50)->nullable();
            $table->string('og_url', 500)->nullable();
            $table->string('og_site_name', 255)->nullable();

            // Twitter Card Meta
            $table->string('twitter_card', 50)->nullable();
            $table->string('twitter_title', 255)->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image', 500)->nullable();

            // Structured Data
            $table->json('json_ld')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_routes');
    }
};
