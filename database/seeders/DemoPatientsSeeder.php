<?php

namespace Database\Seeders;

use App\Models\ChronicDiseaseType;
use App\Models\Clinic;
use App\Models\Examination;
use App\Models\GrowthMeasurement;
use App\Models\LabTestResult;
use App\Models\LabTestType;
use App\Models\Patient;
use App\Models\PatientChronicDisease;
use App\Models\VaccinationRecord;
use App\Models\VaccinationType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoPatientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first clinic (created in DemoUsersSeeder)
        $clinic = Clinic::first();

        if (!$clinic) {
            $this->command->error('لا توجد عيادة! يرجى تشغيل DemoUsersSeeder أولاً.');
            return;
        }

        $doctor = $clinic->doctor;

        $this->command->info('========================================');
        $this->command->info('إنشاء 10 مرضى أطفال مع بيانات طبية شاملة');
        $this->command->info('========================================');

        // Define 10 pediatric patients with realistic Arabic names
        $patientsData = [
            [
                'full_name' => 'أحمد محمد العلي',
                'gender' => 'male',
                'age_months' => 6,
                'blood_type' => 'A+',
                'phone' => '+966501111111',
                'allergies' => 'لا يوجد',
                'medical_history' => 'ولادة طبيعية، وزن الولادة 3.2 كغ',
            ],
            [
                'full_name' => 'فاطمة سعد الخالدي',
                'gender' => 'female',
                'age_months' => 12,
                'blood_type' => 'O+',
                'phone' => '+966502222222',
                'allergies' => 'حساسية من البيض',
                'medical_history' => 'ولادة قيصرية، خداج طفيف (36 أسبوع)',
            ],
            [
                'full_name' => 'عبدالله يوسف السالم',
                'gender' => 'male',
                'age_months' => 24,
                'blood_type' => 'B+',
                'phone' => '+966503333333',
                'allergies' => 'لا يوجد',
                'medical_history' => 'ولادة طبيعية، وزن الولادة 3.5 كغ',
            ],
            [
                'full_name' => 'نورة خالد العتيبي',
                'gender' => 'female',
                'age_months' => 36,
                'blood_type' => 'AB+',
                'phone' => '+966504444444',
                'allergies' => 'حساسية من الفول السوداني',
                'medical_history' => 'ولادة طبيعية، يرقان وليدي خفيف',
            ],
            [
                'full_name' => 'سلطان عبدالرحمن الدوسري',
                'gender' => 'male',
                'age_months' => 48,
                'blood_type' => 'O-',
                'phone' => '+966505555555',
                'allergies' => 'لا يوجد',
                'medical_history' => 'ولادة طبيعية، وزن الولادة 3.8 كغ',
            ],
            [
                'full_name' => 'ريم فيصل الغامدي',
                'gender' => 'female',
                'age_months' => 18,
                'blood_type' => 'A-',
                'phone' => '+966506666666',
                'allergies' => 'حساسية من البنسلين',
                'medical_history' => 'ولادة طبيعية، التهابات أذن متكررة',
            ],
            [
                'full_name' => 'محمد ناصر الحربي',
                'gender' => 'male',
                'age_months' => 30,
                'blood_type' => 'B-',
                'phone' => '+966507777777',
                'allergies' => 'لا يوجد',
                'medical_history' => 'ولادة قيصرية، وزن الولادة 4.1 كغ',
            ],
            [
                'full_name' => 'لمى سعود الشمري',
                'gender' => 'female',
                'age_months' => 42,
                'blood_type' => 'O+',
                'phone' => '+966508888888',
                'allergies' => 'حساسية موسمية',
                'medical_history' => 'ولادة طبيعية، ربو طفولي',
            ],
            [
                'full_name' => 'تركي ماجد القحطاني',
                'gender' => 'male',
                'age_months' => 9,
                'blood_type' => 'AB-',
                'phone' => '+966509999999',
                'allergies' => 'لا يوجد',
                'medical_history' => 'ولادة طبيعية، وزن الولادة 3.0 كغ',
            ],
            [
                'full_name' => 'سارة إبراهيم المطيري',
                'gender' => 'female',
                'age_months' => 60,
                'blood_type' => 'A+',
                'phone' => '+966500000000',
                'allergies' => 'حساسية من الغبار',
                'medical_history' => 'ولادة طبيعية، نقص فيتامين د سابق',
            ],
        ];

        // Get vaccination types
        $vaccinationTypes = VaccinationType::where('is_active', true)->get();

        // Get chronic disease types
        $chronicDiseaseTypes = ChronicDiseaseType::where('is_active', true)->get();

        // Get lab test types
        $labTestTypes = LabTestType::where('is_active', true)->get();

        foreach ($patientsData as $index => $patientData) {
            $dateOfBirth = Carbon::now()->subMonths($patientData['age_months']);

            // Create patient
            $patient = Patient::create([
                'file_number' => Patient::generateFileNumber($clinic->id),
                'full_name' => $patientData['full_name'],
                'date_of_birth' => $dateOfBirth,
                'gender' => $patientData['gender'],
                'phone' => $patientData['phone'],
                'blood_type' => $patientData['blood_type'],
                'allergies' => $patientData['allergies'],
                'medical_history' => $patientData['medical_history'],
                'emergency_contact_name' => 'ولي الأمر',
                'emergency_contact_phone' => $patientData['phone'],
            ]);

            // Attach patient to clinic
            $patient->clinics()->attach($clinic->id);

            $this->command->info("✓ تم إنشاء المريض: {$patient->full_name} (العمر: {$patientData['age_months']} شهر)");

            // Create vaccinations based on age
            $this->createVaccinations($patient, $vaccinationTypes, $patientData['age_months'], $doctor);

            // Create growth measurements over time
            $this->createGrowthMeasurements($patient, $patientData['age_months'], $patientData['gender'], $doctor);

            // Create examinations
            $this->createExaminations($patient, $clinic, $doctor, $patientData['age_months']);

            // Create lab tests
            $this->createLabTests($patient, $labTestTypes, $doctor);

            // Create chronic diseases for some patients
            if (in_array($index, [3, 7, 9])) { // Patients with chronic conditions
                $this->createChronicDiseases($patient, $chronicDiseaseTypes, $doctor);
            }
        }

        $this->command->newLine();
        $this->command->info('========================================');
        $this->command->info('تم إنشاء 10 مرضى مع بيانات طبية شاملة');
        $this->command->info('========================================');
    }

    /**
     * Create vaccinations for a patient based on age.
     */
    private function createVaccinations(Patient $patient, $vaccinationTypes, int $ageMonths, $doctor): void
    {
        // Filter vaccinations that should have been given by this age
        $applicableVaccinations = $vaccinationTypes->filter(function ($vax) use ($ageMonths) {
            return $vax->recommended_age_months <= $ageMonths;
        });

        foreach ($applicableVaccinations as $vaccinationType) {
            $dosesGiven = min(
                $vaccinationType->doses_required,
                max(1, floor(($ageMonths - $vaccinationType->recommended_age_months) / 2) + 1)
            );

            for ($dose = 1; $dose <= $dosesGiven; $dose++) {
                $vaccinationDate = Carbon::now()
                    ->subMonths($ageMonths)
                    ->addMonths($vaccinationType->recommended_age_months + ($dose - 1) * 2);

                // Only create if vaccination date is in the past
                if ($vaccinationDate->isFuture()) {
                    continue;
                }

                VaccinationRecord::create([
                    'patient_id' => $patient->id,
                    'vaccination_type_id' => $vaccinationType->id,
                    'administered_by_user_id' => $doctor->id,
                    'vaccination_date' => $vaccinationDate,
                    'dose_number' => $dose,
                    'batch_number' => 'BATCH-' . strtoupper(substr(md5(rand()), 0, 8)),
                    'manufacturer' => $this->getRandomManufacturer(),
                    'site' => $this->getRandomInjectionSite(),
                    'status' => 'completed',
                    'next_dose_due_date' => $dose < $vaccinationType->doses_required
                        ? $vaccinationDate->copy()->addDays($vaccinationType->interval_days ?? 60)
                        : ($vaccinationType->booster_after_months
                            ? $vaccinationDate->copy()->addMonths($vaccinationType->booster_after_months)
                            : null),
                ]);
            }
        }
    }

    /**
     * Create growth measurements for a patient.
     */
    private function createGrowthMeasurements(Patient $patient, int $currentAgeMonths, string $gender, $doctor): void
    {
        // WHO growth standards reference values (approximate)
        $maleGrowth = [
            0 => ['weight' => 3.3, 'height' => 49.9, 'head' => 34.5],
            3 => ['weight' => 6.4, 'height' => 61.4, 'head' => 40.5],
            6 => ['weight' => 7.9, 'height' => 67.6, 'head' => 43.3],
            9 => ['weight' => 9.2, 'height' => 72.0, 'head' => 45.0],
            12 => ['weight' => 9.9, 'height' => 75.7, 'head' => 46.1],
            18 => ['weight' => 11.1, 'height' => 82.3, 'head' => 47.4],
            24 => ['weight' => 12.2, 'height' => 87.8, 'head' => 48.2],
            36 => ['weight' => 14.3, 'height' => 96.1, 'head' => 49.5],
            48 => ['weight' => 16.3, 'height' => 103.3, 'head' => 50.4],
            60 => ['weight' => 18.3, 'height' => 110.0, 'head' => 51.0],
        ];

        $femaleGrowth = [
            0 => ['weight' => 3.2, 'height' => 49.1, 'head' => 33.9],
            3 => ['weight' => 5.8, 'height' => 59.8, 'head' => 39.5],
            6 => ['weight' => 7.3, 'height' => 65.7, 'head' => 42.0],
            9 => ['weight' => 8.6, 'height' => 70.1, 'head' => 43.8],
            12 => ['weight' => 9.2, 'height' => 74.0, 'head' => 44.9],
            18 => ['weight' => 10.4, 'height' => 80.7, 'head' => 46.2],
            24 => ['weight' => 11.5, 'height' => 86.4, 'head' => 47.1],
            36 => ['weight' => 13.9, 'height' => 95.1, 'head' => 48.4],
            48 => ['weight' => 16.0, 'height' => 102.7, 'head' => 49.3],
            60 => ['weight' => 18.0, 'height' => 109.4, 'head' => 50.0],
        ];

        $growthData = $gender === 'male' ? $maleGrowth : $femaleGrowth;

        // Create measurements at key milestones up to current age
        $milestones = [0, 3, 6, 9, 12, 18, 24, 36, 48, 60];

        foreach ($milestones as $milestone) {
            if ($milestone > $currentAgeMonths) {
                break;
            }

            $baseData = $growthData[$milestone] ?? $this->interpolateGrowth($growthData, $milestone);

            // Add some realistic variation (±5%)
            $variation = (rand(-50, 50) / 1000);
            $weight = round($baseData['weight'] * (1 + $variation), 2);
            $height = round($baseData['height'] * (1 + $variation), 2);
            $head = round($baseData['head'] * (1 + $variation / 2), 2);

            $measurementDate = Carbon::now()->subMonths($currentAgeMonths - $milestone);

            $measurement = GrowthMeasurement::create([
                'patient_id' => $patient->id,
                'measured_by_user_id' => $doctor->id,
                'measurement_date' => $measurementDate,
                'age_months' => $milestone,
                'weight_kg' => $weight,
                'height_cm' => $height,
                'head_circumference_cm' => $milestone <= 36 ? $head : null, // Head only measured up to 3 years
                'interpretation' => $this->getGrowthInterpretation($variation),
                'notes' => $milestone == 0 ? 'قياسات الولادة' : null,
            ]);

            // Calculate BMI and percentiles
            $measurement->calculateBmi();
            $measurement->calculatePercentiles();
            $measurement->save();
        }
    }

    /**
     * Interpolate growth data for ages not in the reference.
     */
    private function interpolateGrowth(array $growthData, int $ageMonths): array
    {
        $ages = array_keys($growthData);
        $lower = 0;
        $upper = end($ages);

        foreach ($ages as $age) {
            if ($age <= $ageMonths) {
                $lower = $age;
            }
            if ($age >= $ageMonths) {
                $upper = $age;
                break;
            }
        }

        if ($lower === $upper) {
            return $growthData[$lower];
        }

        $ratio = ($ageMonths - $lower) / ($upper - $lower);

        return [
            'weight' => $growthData[$lower]['weight'] + ($growthData[$upper]['weight'] - $growthData[$lower]['weight']) * $ratio,
            'height' => $growthData[$lower]['height'] + ($growthData[$upper]['height'] - $growthData[$lower]['height']) * $ratio,
            'head' => $growthData[$lower]['head'] + ($growthData[$upper]['head'] - $growthData[$lower]['head']) * $ratio,
        ];
    }

    /**
     * Create examinations for a patient.
     */
    private function createExaminations(Patient $patient, Clinic $clinic, $doctor, int $ageMonths): void
    {
        // Standard well-child visit schedule
        $visitSchedule = [
            ['age' => 0, 'type' => 'فحص الولادة', 'complaint' => 'فحص روتيني للمولود'],
            ['age' => 1, 'type' => 'فحص الشهر الأول', 'complaint' => 'فحص روتيني ومتابعة النمو'],
            ['age' => 2, 'type' => 'فحص الشهرين', 'complaint' => 'فحص روتيني وتطعيمات'],
            ['age' => 4, 'type' => 'فحص الأربعة أشهر', 'complaint' => 'فحص روتيني وتطعيمات'],
            ['age' => 6, 'type' => 'فحص الستة أشهر', 'complaint' => 'فحص روتيني وإدخال الأطعمة الصلبة'],
            ['age' => 9, 'type' => 'فحص التسعة أشهر', 'complaint' => 'فحص روتيني وتقييم النمو'],
            ['age' => 12, 'type' => 'فحص السنة الأولى', 'complaint' => 'فحص سنوي شامل'],
            ['age' => 18, 'type' => 'فحص السنة والنصف', 'complaint' => 'فحص روتيني وتطعيمات'],
            ['age' => 24, 'type' => 'فحص السنتين', 'complaint' => 'فحص سنوي وتقييم التطور'],
            ['age' => 36, 'type' => 'فحص الثلاث سنوات', 'complaint' => 'فحص سنوي شامل'],
            ['age' => 48, 'type' => 'فحص الأربع سنوات', 'complaint' => 'فحص ما قبل المدرسة'],
            ['age' => 60, 'type' => 'فحص الخمس سنوات', 'complaint' => 'فحص دخول المدرسة'],
        ];

        $diagnoses = [
            'الطفل بصحة جيدة، نمو طبيعي',
            'نمو وتطور طبيعي حسب العمر',
            'لا توجد مشاكل صحية، متابعة روتينية',
            'طفل سليم، التطعيمات حسب الجدول',
        ];

        foreach ($visitSchedule as $visit) {
            if ($visit['age'] > $ageMonths) {
                break;
            }

            $examDate = Carbon::now()->subMonths($ageMonths - $visit['age']);

            // Generate examination number
            $examNumber = 'EX-' . $clinic->id . '-' . date('Ymd', $examDate->timestamp) . '-' . rand(1000, 9999);

            Examination::create([
                'patient_id' => $patient->id,
                'user_id' => $doctor->id,
                'clinic_id' => $clinic->id,
                'examination_number' => $examNumber,
                'examination_date' => $examDate,
                'chief_complaint' => $visit['complaint'],
                'present_illness_history' => $visit['type'],
                'temperature' => round(36.5 + (rand(-5, 5) / 10), 1),
                'pulse_rate' => rand(80, 140),
                'respiratory_rate' => rand(20, 40),
                'oxygen_saturation' => rand(97, 100),
                'physical_examination' => 'فحص عام: مظهر صحي، نشيط. القلب: أصوات طبيعية. الرئتين: صافيتان. البطن: طري، لا ألم.',
                'diagnosis' => $diagnoses[array_rand($diagnoses)],
                'treatment_plan' => 'استمرار الرضاعة/التغذية السليمة، متابعة جدول التطعيمات',
                'follow_up_date' => $examDate->copy()->addMonths(3),
                'follow_up_notes' => 'موعد المتابعة الروتيني',
                'status' => 'completed',
            ]);
        }

        // Add some sick visits randomly
        if ($ageMonths > 3 && rand(1, 100) > 50) {
            $sickVisits = [
                ['complaint' => 'حرارة وسعال', 'diagnosis' => 'التهاب المجاري التنفسية العليا', 'treatment' => 'خافض حرارة، سوائل كافية، راحة'],
                ['complaint' => 'إسهال وقيء', 'diagnosis' => 'التهاب معدي معوي فيروسي', 'treatment' => 'محلول الجفاف، تغذية خفيفة'],
                ['complaint' => 'طفح جلدي', 'diagnosis' => 'التهاب جلدي تحسسي', 'treatment' => 'مرهم مضاد للحساسية، تجنب المسببات'],
                ['complaint' => 'ألم في الأذن', 'diagnosis' => 'التهاب الأذن الوسطى الحاد', 'treatment' => 'مضاد حيوي، خافض حرارة'],
            ];

            $sickVisit = $sickVisits[array_rand($sickVisits)];
            $visitDate = Carbon::now()->subDays(rand(30, $ageMonths * 15));

            Examination::create([
                'patient_id' => $patient->id,
                'user_id' => $doctor->id,
                'clinic_id' => $clinic->id,
                'examination_number' => 'EX-' . $clinic->id . '-' . date('Ymd', $visitDate->timestamp) . '-' . rand(1000, 9999),
                'examination_date' => $visitDate,
                'chief_complaint' => $sickVisit['complaint'],
                'present_illness_history' => 'بدأت الأعراض منذ يومين',
                'temperature' => round(37.5 + (rand(0, 15) / 10), 1),
                'pulse_rate' => rand(100, 150),
                'respiratory_rate' => rand(25, 45),
                'oxygen_saturation' => rand(95, 99),
                'physical_examination' => 'طفل متنبه، علامات مرض خفيف',
                'diagnosis' => $sickVisit['diagnosis'],
                'treatment_plan' => $sickVisit['treatment'],
                'follow_up_date' => $visitDate->copy()->addDays(7),
                'follow_up_notes' => 'مراجعة إذا لم تتحسن الأعراض',
                'status' => 'completed',
            ]);
        }
    }

    /**
     * Create lab tests for a patient.
     */
    private function createLabTests(Patient $patient, $labTestTypes, $doctor): void
    {
        // Common pediatric lab tests
        $commonTests = ['cbc', 'hemoglobin', 'glucose_fasting', 'vitamin_d'];

        $testsToCreate = $labTestTypes->filter(function ($test) use ($commonTests) {
            return in_array($test->key, $commonTests);
        });

        foreach ($testsToCreate as $testType) {
            $testDate = Carbon::now()->subDays(rand(30, 180));

            LabTestResult::create([
                'patient_id' => $patient->id,
                'lab_test_type_id' => $testType->id,
                'ordered_by_user_id' => $doctor->id,
                'test_date' => $testDate,
                'result_value' => $this->generateLabResult($testType),
                'result_text' => $this->generateLabResultText($testType),
                'status' => 'completed',
                'lab_name' => 'مختبر المدينة الطبي',
                'lab_reference_number' => 'LAB-' . strtoupper(substr(md5(rand()), 0, 8)),
                'interpretation' => $this->generateLabInterpretation($testType),
                'doctor_notes' => rand(1, 100) > 70 ? 'نتائج طبيعية، لا حاجة لإعادة الفحص' : null,
            ]);
        }
    }

    /**
     * Create chronic diseases for a patient.
     */
    private function createChronicDiseases(Patient $patient, $chronicDiseaseTypes, $doctor): void
    {
        // Use available chronic conditions (asthma is available in seeder)
        $availableConditions = ['asthma', 'epilepsy'];

        $conditionsToCreate = $chronicDiseaseTypes->filter(function ($disease) use ($availableConditions) {
            return in_array($disease->key, $availableConditions);
        })->take(1);

        // If no matching conditions, just use the first available one
        if ($conditionsToCreate->isEmpty() && $chronicDiseaseTypes->isNotEmpty()) {
            $conditionsToCreate = $chronicDiseaseTypes->take(1);
        }

        foreach ($conditionsToCreate as $diseaseType) {
            $diagnosisDate = Carbon::now()->subMonths(rand(3, 12));

            PatientChronicDisease::create([
                'patient_id' => $patient->id,
                'chronic_disease_type_id' => $diseaseType->id,
                'diagnosed_by_user_id' => $doctor->id,
                'diagnosis_date' => $diagnosisDate,
                'severity' => ['mild', 'moderate'][rand(0, 1)],
                'status' => 'active',
                'treatment_plan' => $this->getChronicDiseaseTreatment($diseaseType->key),
                'notes' => 'تم تشخيص الحالة بناءً على الأعراض السريرية',
                'last_followup_date' => Carbon::now()->subDays(rand(7, 30)),
                'next_followup_date' => Carbon::now()->addDays(rand(30, 90)),
            ]);
        }
    }

    /**
     * Get random vaccine manufacturer.
     */
    private function getRandomManufacturer(): string
    {
        $manufacturers = [
            'Pfizer',
            'GlaxoSmithKline',
            'Sanofi Pasteur',
            'Merck',
            'Novartis',
        ];
        return $manufacturers[array_rand($manufacturers)];
    }

    /**
     * Get random injection site.
     */
    private function getRandomInjectionSite(): string
    {
        $sites = [
            'الفخذ الأيمن',
            'الفخذ الأيسر',
            'العضد الأيمن',
            'العضد الأيسر',
        ];
        return $sites[array_rand($sites)];
    }

    /**
     * Get growth interpretation based on variation.
     * Values: underweight, normal, overweight, obese
     */
    private function getGrowthInterpretation(float $variation): string
    {
        if ($variation < -0.03) {
            return 'underweight';
        } elseif ($variation > 0.05) {
            return 'overweight';
        }
        return 'normal';
    }

    /**
     * Generate lab result value.
     */
    private function generateLabResult($testType): ?string
    {
        if ($testType->normal_range_min && $testType->normal_range_max) {
            $value = rand(
                    (int)($testType->normal_range_min * 10),
                    (int)($testType->normal_range_max * 10)
                ) / 10;
            return (string)$value;
        }
        return null;
    }

    /**
     * Generate lab result text.
     */
    private function generateLabResultText($testType): ?string
    {
        if ($testType->key === 'cbc') {
            return 'جميع المكونات ضمن الحدود الطبيعية';
        }
        return null;
    }

    /**
     * Generate lab interpretation.
     */
    private function generateLabInterpretation($testType): string
    {
        $rand = rand(1, 100);
        if ($rand > 90) {
            return 'abnormal_low';
        } elseif ($rand > 80) {
            return 'abnormal_high';
        }
        return 'normal';
    }

    /**
     * Get treatment plan for chronic disease.
     */
    private function getChronicDiseaseTreatment(string $diseaseKey): string
    {
        $treatments = [
            'asthma' => 'بخاخ موسع قصبات عند الحاجة، تجنب المحفزات، متابعة دورية',
            'epilepsy' => 'أدوية مضادة للصرع، تجنب المحفزات، متابعة دورية مع تخطيط الدماغ',
            'diabetes_type1' => 'العلاج بالأنسولين، مراقبة السكر بانتظام، التحكم بالنظام الغذائي',
            'hypertension' => 'مراقبة الضغط بانتظام، حمية قليلة الصوديوم، الأدوية',
            'hypothyroidism' => 'العلاج الهرموني التعويضي، مراقبة TSH بانتظام',
        ];

        return $treatments[$diseaseKey] ?? 'متابعة دورية مع الطبيب';
    }
}
