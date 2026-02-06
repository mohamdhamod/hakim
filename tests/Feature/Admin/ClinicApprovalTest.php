<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $doctor;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user with admin role
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin');
        
        // Create doctor user
        $this->doctor = User::factory()->create();
    }

    public function test_admin_can_view_pending_clinics(): void
    {
        $this->actingAs($this->admin);

        $specialty = Specialty::factory()->create();
        
        Clinic::factory()->count(3)->create([
            'specialty_id' => $specialty->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('dashboard.clinics.pending'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.clinics.pending');
    }

    public function test_admin_can_approve_clinic(): void
    {
        $this->actingAs($this->admin);

        $specialty = Specialty::factory()->create();
        
        $clinic = Clinic::factory()->create([
            'user_id' => $this->doctor->id,
            'specialty_id' => $specialty->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('dashboard.clinics.approve', $clinic->id));

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('clinics', [
            'id' => $clinic->id,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_reject_clinic(): void
    {
        $this->actingAs($this->admin);

        $specialty = Specialty::factory()->create();
        
        $clinic = Clinic::factory()->create([
            'user_id' => $this->doctor->id,
            'specialty_id' => $specialty->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('dashboard.clinics.reject', $clinic->id), [
            'rejection_reason' => 'Invalid documentation',
        ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('clinics', [
            'id' => $clinic->id,
            'status' => 'rejected',
        ]);
    }

    public function test_non_admin_cannot_approve_clinic(): void
    {
        $this->actingAs($this->doctor);

        $specialty = Specialty::factory()->create();
        
        $clinic = Clinic::factory()->create([
            'special ty_id' => $specialty->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('dashboard.clinics.approve', $clinic->id));

        $response->assertStatus(403);
    }

    public function test_doctor_sees_pending_approval_page(): void
    {
        $this->actingAs($this->doctor);

        $specialty = Specialty::factory()->create();
        
        $clinic = Clinic::factory()->create([
            'user_id' => $this->doctor->id,
            'specialty_id' => $specialty->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('clinic.workspace', ['locale' => 'en']));

        $response->assertStatus(200);
        $response->assertViewIs('clinic.pending-approval');
    }

    public function test_doctor_sees_rejected_page(): void
    {
        $this->actingAs($this->doctor);

        $specialty = Specialty::factory()->create();
        
        $clinic = Clinic::factory()->create([
            'user_id' => $this->doctor->id,
            'specialty_id' => $specialty->id,
            'status' => 'rejected',
        ]);

        $response = $this->get(route('clinic.workspace', ['locale' => 'en']));

        $response->assertStatus(200);
        $response->assertViewIs('clinic.rejected');
    }
}
