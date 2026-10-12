<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\SchoolMembership;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SchoolController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Get teacher's school membership
        $membership = SchoolMembership::where('user_id', $user->id)
            ->whereIn('role', ['teacher', 'admin'])
            ->first();

        $school = $membership ? School::with('classes')->find($membership->school_id) : School::with('classes')->first();

        $schoolId = $school?->id;

        $memberships = SchoolMembership::with(['user', 'schoolClass'])
            ->where('school_id', $schoolId)
            ->where('role', 'student')
            ->latest()
            ->get();

        $classes = SchoolClass::where('school_id', $schoolId)->get();

        $stats = [
            'total_students' => $memberships->count(),
            'approved_students' => $memberships->where('status', 'approved')->count(),
            'pending_students' => $memberships->where('status', 'pending')->count(),
            'total_classes' => $classes->count(),
        ];

        return Inertia::render('School/Dashboard', [
            'school' => $school,
            'memberships' => $memberships,
            'classes' => $classes,
            'stats' => $stats,
        ]);
    }

    public function verifyStudent(Request $request, int $id): RedirectResponse
    {
        $membership = SchoolMembership::with('user')->findOrFail($id);

        $membership->update([
            'status' => 'approved',
            'verified_at' => now(),
            'school_class_id' => $request->input('school_class_id', $membership->school_class_id),
        ]);

        // Activate user status
        $membership->user->update(['status' => 'active']);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'student_membership_verified',
            'entity_type' => 'SchoolMembership',
            'entity_id' => $membership->id,
            'new_values' => ['student_id' => $membership->user_id, 'school_id' => $membership->school_id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Siswa '.$membership->user->name.' berhasil diverifikasi dan akunnya kini aktif.');
    }

    public function createClass(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'name' => ['required', 'string', 'max:100'],
            'grade_level' => ['nullable', 'string', 'max:20'],
            'academic_year' => ['nullable', 'string', 'max:20'],
        ]);

        SchoolClass::create($validated);

        return redirect()->back()->with('success', 'Kelas baru berhasil ditambahkan.');
    }
}
