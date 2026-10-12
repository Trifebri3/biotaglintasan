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
        Schema::create('green_actions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // energy, travel, waste, tree_care
            $table->text('description')->nullable();
            $table->integer('impact_points')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_action_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('green_action_id')->constrained('green_actions')->cascadeOnDelete();
            $table->timestamp('completed_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('emission_factors', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // electricity, transport_gasoline, transport_diesel, transport_motorcycle, transport_public
            $table->string('unit'); // kWh, km, liter
            $table->decimal('co2e_factor', 8, 4); // kg CO2e per unit
            $table->string('version')->default('v1.0-2026');
            $table->string('source_reference'); // e.g. ESDM & IPCC 2026
            $table->date('effective_date');
            $table->timestamps();
        });

        Schema::create('emission_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('emission_factor_id')->nullable()->constrained('emission_factors')->nullOnDelete();
            $table->string('category');
            $table->decimal('activity_value', 10, 2);
            $table->string('unit');
            $table->decimal('calculated_co2e', 10, 2);
            $table->date('period_start');
            $table->date('period_end');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emission_entries');
        Schema::dropIfExists('emission_factors');
        Schema::dropIfExists('user_action_logs');
        Schema::dropIfExists('green_actions');
    }
};
