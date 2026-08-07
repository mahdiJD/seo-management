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
        Schema::create('seo_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('site_name', 255)->nullable();
            $table->string('default_title', 255)->nullable();
            $table->text('default_description')->nullable();
            $table->string('default_canonical', 500)->nullable();
            $table->string('default_robots', 50)->nullable();
            $table->string('default_og_image', 500)->nullable();
            $table->string('default_og_type', 50)->nullable();
            $table->string('default_og_site_name', 255)->nullable();
            $table->string('default_twitter_card', 50)->nullable();
            $table->string('default_twitter_image', 500)->nullable();
            $table->json('default_json_ld')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
