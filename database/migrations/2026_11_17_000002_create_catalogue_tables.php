<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('icon', 60)->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->string('name');
            $table->string('category', 60)->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('slug', 191)->unique();
            $table->json('title');
            $table->json('tagline')->nullable();
            $table->json('excerpt')->nullable();
            $table->json('body')->nullable();
            $table->string('icon', 60)->nullable();
            $table->json('features')->nullable();
            $table->json('process_steps')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
        });

        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->json('name');
            $table->json('tagline')->nullable();
            $table->json('summary')->nullable();
            $table->json('body')->nullable();
            $table->string('icon', 60)->nullable();
            $table->string('demo_url')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('is_flagship')->default(false);
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
        });

        Schema::create('solution_feature_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solution_id')->constrained()->cascadeOnDelete();
            $table->string('key', 60);
            $table->json('name');
            $table->json('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('solution_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solution_feature_group_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 191)->unique();
            $table->json('title');
            $table->json('description')->nullable();
            $table->string('icon', 60)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->string('name');
            $table->string('website_url')->nullable();
            $table->foreignId('industry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->nullable();
            $table->boolean('show_logo')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('industry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug', 191)->unique();
            $table->string('type', 20)->default('live');
            $table->json('title');
            $table->json('tagline')->nullable();
            $table->json('summary')->nullable();
            $table->json('challenge')->nullable();
            $table->json('approach')->nullable();
            $table->json('outcome')->nullable();
            $table->string('live_url')->nullable();
            $table->json('metrics')->nullable();
            $table->date('launched_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
            $table->index('sort_order');
        });

        Schema::create('project_service', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'service_id']);
        });

        Schema::create('project_technology', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'technology_id']);
        });

        Schema::create('project_solution_feature', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('solution_feature_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'solution_feature_id']);
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->json('author_role')->nullable();
            $table->json('quote');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['testimonials', 'project_solution_feature', 'project_technology', 'project_service', 'projects', 'clients',
            'solution_features', 'solution_feature_groups', 'solutions', 'services', 'technologies', 'industries'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
