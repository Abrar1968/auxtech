<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('password');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->json('value')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable');
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->string('canonical')->nullable();
            $table->string('robots', 60)->nullable();
            $table->timestamps();
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path', 191)->unique();
            $table->string('to_url', 500);
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();
        });

        Schema::create('failed_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task');
            $table->json('payload')->nullable();
            $table->text('error')->nullable();
            $table->unsignedSmallInteger('attempts')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_tasks');
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('seo_metas');
        Schema::dropIfExists('settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'last_login_at']);
        });
    }
};
