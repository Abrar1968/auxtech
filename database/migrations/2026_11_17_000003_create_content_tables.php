<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->json('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->json('name');
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('post_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug', 191)->unique();
            $table->json('title');
            $table->json('excerpt')->nullable();
            $table->json('body')->nullable();
            $table->unsignedSmallInteger('reading_minutes')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->json('title');
            $table->json('body')->nullable();
            $table->string('template', 40)->default('default');
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 191)->unique();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('industry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->nullable();
            $table->json('title');
            $table->json('intro')->nullable();
            $table->json('body')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('faqable');
            $table->string('group', 60)->nullable()->index();
            $table->json('question');
            $table->json('answer');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('planable');
            $table->json('name');
            $table->json('description')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 3)->default('BDT');
            $table->string('period', 30)->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('role')->nullable();
            $table->json('bio')->nullable();
            $table->json('socials')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->json('label');
            $table->string('value', 40);
            $table->string('suffix', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['stats', 'team_members', 'pricing_plans', 'faqs', 'landing_pages', 'pages', 'post_tag', 'posts', 'tags', 'post_categories'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
