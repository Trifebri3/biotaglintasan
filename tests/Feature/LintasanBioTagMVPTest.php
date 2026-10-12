<?php

namespace Tests\Feature;

use App\Models\QRCode;
use App\Models\School;
use App\Models\SchoolMembership;
use App\Models\Tree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LintasanBioTagMVPTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_accessible(): void
    {
        $this->get(route('home'))->assertStatus(200);
        $this->get(route('public.map'))->assertStatus(200);
        $this->get(route('public.education'))->assertStatus(200);
        $this->get(route('public.about'))->assertStatus(200);
    }

    public function test_pending_user_is_redirected_to_pending_verification_page(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'status' => 'pending_verification',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('user.pending'));

        $this->actingAs($user)
            ->get(route('user.pending'))
            ->assertStatus(200);
    }

    public function test_active_user_can_access_user_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('user.dashboard'));

        $this->actingAs($user)
            ->get(route('user.dashboard'))
            ->assertStatus(200);
    }

    public function test_user_can_adopt_tree_using_valid_qr_token(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);

        $tree = Tree::create([
            'tree_code' => 'TR-TEST-001',
            'species_name' => 'Mangifera indica',
            'common_name' => 'Pohon Mangga Uji Coba',
            'status' => 'verified',
            'co2_absorption_kg_year' => 20.00,
        ]);

        $qr = QRCode::create([
            'tree_id' => $tree->id,
            'token' => 'qr_test_token_123',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post(route('user.adopt-tree'), [
            'token' => 'qr_test_token_123',
            'notes' => 'Catatan adopsi keluarga',
        ]);

        $response->assertRedirect(route('user.dashboard'));

        $this->assertDatabaseHas('adoptions', [
            'tree_id' => $tree->id,
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $this->assertEquals('adopted', $tree->fresh()->status);
        $this->assertEquals('used', $qr->fresh()->status);
    }

    public function test_user_can_record_household_emission(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post(route('user.record-emission'), [
            'category' => 'electricity',
            'activity_value' => 50,
            'period_start' => '2026-10-01',
            'period_end' => '2026-10-10',
            'notes' => 'Tagihan listrik pekanan',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('emission_entries', [
            'user_id' => $user->id,
            'activity_value' => 50,
        ]);
    }

    public function test_teacher_can_verify_student_membership(): void
    {
        $teacher = User::factory()->create([
            'role' => 'school_admin',
            'status' => 'active',
        ]);

        $student = User::factory()->create([
            'role' => 'user',
            'status' => 'pending_verification',
        ]);

        $school = School::create([
            'name' => 'SD Uji Coba',
            'code' => 'SCH-TEST-01',
        ]);

        $membership = SchoolMembership::create([
            'school_id' => $school->id,
            'user_id' => $student->id,
            'role' => 'student',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($teacher)->post(route('school.verify-student', ['id' => $membership->id]));

        $response->assertSessionHas('success');
        $this->assertEquals('approved', $membership->fresh()->status);
        $this->assertEquals('active', $student->fresh()->status);
    }

    public function test_unauthorized_user_cannot_access_biotag_super_admin(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('biotag.dashboard'))
            ->assertStatus(403);
    }

    public function test_biotag_super_admin_can_access_biotag_dashboard(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'biotag_super_admin',
            'status' => 'active',
        ]);

        $this->actingAs($superAdmin)
            ->get(route('biotag.dashboard'))
            ->assertStatus(200);
    }
}
