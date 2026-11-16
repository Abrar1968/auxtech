<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('phone', 20)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('budget_range', 60)->nullable();
            $table->text('message')->nullable();
            $table->string('source', 30);
            $table->string('status', 20)->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('gclid')->nullable();
            $table->string('fbclid')->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('landing_url', 500)->nullable();
            $table->string('page_url', 500)->nullable();
            $table->char('ip_hash', 64)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'created_at']);
        });

        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('conversion_events', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->string('placement', 60)->nullable();
            $table->string('page_url', 500)->nullable();
            $table->string('visitor_id', 64)->nullable()->index();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->string('locale', 5)->default('en');
            $table->string('device', 20)->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversion_events');
        Schema::dropIfExists('lead_notes');
        Schema::dropIfExists('leads');
    }
};
