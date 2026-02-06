<?php

namespace Tests\Feature\Clinic;

use App\Models\Clinic;
use App\Models\Examination;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExaminationManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $doctor;
    protected Clinic $clinic;
    protected Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctor = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->doctor->assignRole('doctor');

        $this->clinic = Clinic::factory()->create([
            'user_id' => $this->doctor->id,
            'status' => 'approved',
        ]);

        $this->patient = Patient::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);
    }

    public function test_doctor_can_create_examination(): void
    {
        $this->actingAs($this->doctor);

        $examinationData = [
            'patient_id' => $this->patient->id,
            'examination_date' => now()->format('Y-m-d\TH:i'),
            'chief_complaint' => 'Headache',
            'diagnosis' => 'Migraine',
            'icd_code' => 'G43.9',
            'treatment_plan' => 'Rest and medication',
            'status' => 'in_progress',
        ];

        $response = $this->post(
            route('clinic.examinations.store', ['locale' => 'en']), 
            $examinationData
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('examinations', [
            'patient_id' => $this->patient->id,
            'clinic_id' => $this->clinic->id,
            'chief_complaint' => 'Headache',
            'icd_code' => 'G43.9',
        ]);
    }

    public function test_doctor_can_view_examination(): void
    {
        $this->actingAs($this->doctor);

        $examination = Examination::factory()->create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $this->patient->id,
        ]);

        $response = $this->get(route('clinic.examinations.show', [
            'locale' => 'en',
            'examination' => $examination->id,
        ]));

        $response->assertSuccessful();
        $response->assertViewIs('clinic.examinations.show');
    }

    public function test_doctor_can_mark_examination_complete(): void
    {
        $this->actingAs($this->doctor);

        $examination = Examination::factory()->create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $this->patient->id,
            'status' => 'in_progress',
        ]);

        $response = $this->post(route('clinic.examinations.complete', [
            'locale' => 'en',
            'examination' => $examination->id,
        ]));

        $response->assertRedirect();
        $this->assertDatabaseHas('examinations', [
            'id' => $examination->id,
            'status' => 'completed',
        ]);
    }

    public function test_icd_code_is_optional_in_examination(): void
    {
        $this->actingAs($this->doctor);

        $examinationData = [
            'patient_id' => $this->patient->id,
            'examination_date' => now()->format('Y-m-d\TH:i'),
            'chief_complaint' => 'Cough',
            'diagnosis' => 'Common cold',
            // icd_code is intentionally omitted
            'status' => 'in_progress',
        ];

        $response = $this->post(
            route('clinic.examinations.store', ['locale' => 'en']), 
            $examinationData
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('examinations', [
            'patient_id' => $this->patient->id,
            'chief_complaint' => 'Cough',
            'icd_code' => null,
        ]);
    }
}
