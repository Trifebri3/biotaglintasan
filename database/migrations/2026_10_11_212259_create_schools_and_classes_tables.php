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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name');
            $table->string('grade_level')->nullable();
            $table->string('academic_year')->nullable();
            $table->timestamps();
        });

        Schema::create('school_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('student'); // student, teacher, admin
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('guardian_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('guardian_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, granted, revoked
            $table->string('policy_version')->default('1.0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardian_consents');
        Schema::dropIfExists('school_memberships');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('schools');
    }
};
