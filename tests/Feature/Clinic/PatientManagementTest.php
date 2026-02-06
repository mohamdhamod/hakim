<?php

namespace Tests\Feature\Clinic;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $doctor;
    protected Clinic $clinic;

    protected function setUp(): void
    {
        parent::setUp();

        // Create doctor user with clinic
        $this->doctor = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->doctor->assignRole('doctor');

        $this->clinic = Clinic::factory()->create([
            'user_id' => $this->doctor->id,
            'status' => 'approved',
        ]);
    }

    public function test_doctor_can_view_patients_list(): void
    {
        $this->actingAs($this->doctor);

        $response = $this->get(route('clinic.patients.index', ['locale' => 'en']));

        $response->assertSuccessful();
        $response->assertViewIs('clinic.patients.index');
    }

    public function test_doctor_can_create_patient(): void
    {
        $this->actingAs($this->doctor);

        $patientData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_type' => 'A+',
            'national_id' => '1234567890',
        ];

        $response = $this->post(route('clinic.patients.store', ['locale' => 'en']), $patientData);

        $response->assertRedirect();
        $this->assertDatabaseHas('patients', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'clinic_id' => $this->clinic->id,
        ]);
    }

    public function test_doctor_can_view_patient_details(): void
    {
        $this->actingAs($this->doctor);

        $patient = Patient::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        $response = $this->get(route('clinic.patients.show', [
            'locale' => 'en',
            'patient' => $patient->file_number,
        ]));

        $response->assertSuccessful();
        $response->assertViewIs('clinic.patients.show');
        $response->assertViewHas('patient', $patient);
    }

    public function test_doctor_can_update_patient(): void
    {
        $this->actingAs($this->doctor);

        $patient = Patient::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => $patient->email,
            'phone' => $patient->phone,
            'date_of_birth' => $patient->date_of_birth,
            'gender' => $patient->gender,
        ];

        $response = $this->put(route('clinic.patients.update', [
            'locale' => 'en',
            'patient' => $patient->file_number,
        ]), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);
    }

    public function test_doctor_cannot_access_another_clinic_patient(): void
    {
        $this->actingAs($this->doctor);

        $otherClinic = Clinic::factory()->create(['status' => 'approved']);
        $otherPatient = Patient::factory()->create([
            'clinic_id' => $otherClinic->id,
        ]);

        $response = $this->get(route('clinic.patients.show', [
            'locale' => 'en',
            'patient' => $otherPatient->file_number,
        ]));

        $response->assertForbidden();
    }
}
