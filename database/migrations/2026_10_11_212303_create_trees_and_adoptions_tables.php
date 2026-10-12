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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->string('tree_code')->unique();
            $table->string('species_name');
            $table->string('common_name');
            $table->text('description')->nullable();
            $table->string('photo_url')->nullable();
            $table->date('planted_date')->nullable();
            $table->string('status')->default('registered'); // registered, verified, adopted, monitored
            $table->decimal('co2_absorption_kg_year', 8, 2)->default(21.77);
            $table->timestamps();
        });

        Schema::create('tree_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('trees')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('public_latitude', 10, 7)->nullable();
            $table->decimal('public_longitude', 10, 7)->nullable();
            $table->string('address_description')->nullable();
            $table->string('visibility')->default('generalized'); // public, generalized, private
            $table->timestamps();
        });

        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('trees')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->string('status')->default('active'); // active, used, disabled
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('adoptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('trees')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('adopted_at');
            $table->string('status')->default('active'); // active, transferred, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('tree_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('trees')->cascadeOnDelete();
            $table->foreignId('observed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('condition_status')->default('healthy'); // thriving, healthy, needs_care, withered
            $table->text('notes')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamp('observed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_observations');
        Schema::dropIfExists('adoptions');
        Schema::dropIfExists('qr_codes');
        Schema::dropIfExists('tree_locations');
        Schema::dropIfExists('trees');
    }
};
