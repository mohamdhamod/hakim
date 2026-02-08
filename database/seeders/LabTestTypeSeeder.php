<?php

namespace Database\Seeders;

use App\Models\LabTestType;
use Illuminate\Database\Seeder;

class LabTestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tests = [
            // Hematology
            [
                'key' => 'cbc',
                'category' => 'Hematology',
                'unit' => null,
                'normal_range_text' => 'Various parameters',
                'order' => 1,
                'ar' => [
                    'name' => 'تعداد الدم الشامل',
                    'description' => 'قياس مكونات الدم المختلفة',
                ],
                'en' => [
                    'name' => 'Complete Blood Count (CBC)',
                    'description' => 'Measures various components of blood',
                ],
            ],
            [
                'key' => 'hemoglobin',
                'category' => 'Hematology',
                'unit' => 'g/dL',
                'normal_range_min' => 12.0,
                'normal_range_max' => 16.0,
                'age_gender_ranges' => [
                    'male_adult' => ['min' => 13.5, 'max' => 17.5],
                    'female_adult' => ['min' => 12.0, 'max' => 15.5],
                    'male_teen_12_18' => ['min' => 13.0, 'max' => 16.5],
                    'female_teen_12_18' => ['min' => 12.0, 'max' => 15.0],
                    'male_child_5_12' => ['min' => 11.5, 'max' => 15.5],
                    'female_child_5_12' => ['min' => 11.5, 'max' => 15.5],
                    'male_child_1_5' => ['min' => 11.0, 'max' => 14.0],
                    'female_child_1_5' => ['min' => 11.0, 'max' => 14.0],
                    'male_infant_0_1' => ['min' => 10.5, 'max' => 13.5],
                    'female_infant_0_1' => ['min' => 10.5, 'max' => 13.5],
                ],
                'order' => 2,
                'ar' => ['name' => 'الهيموجلوبين'],
                'en' => ['name' => 'Hemoglobin (Hb)'],
            ],
            [
                'key' => 'wbc',
                'category' => 'Hematology',
                'unit' => '10^3/μL',
                'normal_range_min' => 4.0,
                'normal_range_max' => 11.0,
                'order' => 3,
                'ar' => ['name' => 'كريات الدم البيضاء'],
                'en' => ['name' => 'White Blood Cells (WBC)'],
            ],
            [
                'key' => 'platelets',
                'category' => 'Hematology',
                'unit' => '10^3/μL',
                'normal_range_min' => 150.0,
                'normal_range_max' => 400.0,
                'order' => 4,
                'ar' => ['name' => 'الصفائح الدموية'],
                'en' => ['name' => 'Platelets'],
            ],

            // Biochemistry
            [
                'key' => 'glucose_fasting',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 70.0,
                'normal_range_max' => 100.0,
                'order' => 10,
                'ar' => ['name' => 'سكر الدم الصائم'],
                'en' => ['name' => 'Blood Glucose (Fasting)'],
            ],
            [
                'key' => 'hba1c',
                'category' => 'Biochemistry',
                'unit' => '%',
                'normal_range_min' => 4.0,
                'normal_range_max' => 5.6,
                'order' => 11,
                'ar' => ['name' => 'الهيموجلوبين السكري'],
                'en' => ['name' => 'HbA1c (Glycated Hemoglobin)'],
            ],
            [
                'key' => 'cholesterol_total',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 0,
                'normal_range_max' => 200.0,
                'order' => 12,
                'ar' => ['name' => 'الكوليسترول الكلي'],
                'en' => ['name' => 'Cholesterol (Total)'],
            ],
            [
                'key' => 'ldl',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 0,
                'normal_range_max' => 100.0,
                'order' => 13,
                'ar' => ['name' => 'الكوليسترول الضار'],
                'en' => ['name' => 'LDL Cholesterol'],
            ],
            [
                'key' => 'hdl',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 40.0,
                'normal_range_max' => 200.0,
                'order' => 14,
                'ar' => ['name' => 'الكوليسترول النافع'],
                'en' => ['name' => 'HDL Cholesterol'],
            ],
            [
                'key' => 'triglycerides',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 0,
                'normal_range_max' => 150.0,
                'order' => 15,
                'ar' => ['name' => 'الدهون الثلاثية'],
                'en' => ['name' => 'Triglycerides'],
            ],
            [
                'key' => 'creatinine',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 0.6,
                'normal_range_max' => 1.2,
                'order' => 16,
                'ar' => ['name' => 'الكرياتينين'],
                'en' => ['name' => 'Creatinine'],
            ],
            [
                'key' => 'urea',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 7.0,
                'normal_range_max' => 20.0,
                'order' => 17,
                'ar' => ['name' => 'اليوريا'],
                'en' => ['name' => 'Urea/BUN'],
            ],

            // Liver Function
            [
                'key' => 'alt',
                'category' => 'Liver Function',
                'unit' => 'U/L',
                'normal_range_min' => 7.0,
                'normal_range_max' => 55.0,
                'order' => 20,
                'ar' => ['name' => 'إنزيم الكبد ALT'],
                'en' => ['name' => 'ALT (SGPT)'],
            ],
            [
                'key' => 'ast',
                'category' => 'Liver Function',
                'unit' => 'U/L',
                'normal_range_min' => 8.0,
                'normal_range_max' => 48.0,
                'order' => 21,
                'ar' => ['name' => 'إنزيم الكبد AST'],
                'en' => ['name' => 'AST (SGOT)'],
            ],
            [
                'key' => 'bilirubin_total',
                'category' => 'Liver Function',
                'unit' => 'mg/dL',
                'normal_range_min' => 0.1,
                'normal_range_max' => 1.2,
                'order' => 22,
                'ar' => ['name' => 'البيليروبين الكلي'],
                'en' => ['name' => 'Total Bilirubin'],
            ],

            // Thyroid Function
            [
                'key' => 'tsh',
                'category' => 'Thyroid Function',
                'unit' => 'mIU/L',
                'normal_range_min' => 0.4,
                'normal_range_max' => 4.0,
                'order' => 30,
                'ar' => ['name' => 'هرمون الغدة الدرقية'],
                'en' => ['name' => 'TSH'],
            ],
            [
                'key' => 'free_t4',
                'category' => 'Thyroid Function',
                'unit' => 'ng/dL',
                'normal_range_min' => 0.8,
                'normal_range_max' => 1.8,
                'order' => 31,
                'ar' => ['name' => 'هرمون T4 الحر'],
                'en' => ['name' => 'Free T4'],
            ],

            // Vitamins & Minerals
            [
                'key' => 'vitamin_d',
                'category' => 'Vitamins & Minerals',
                'unit' => 'ng/mL',
                'normal_range_min' => 30.0,
                'normal_range_max' => 100.0,
                'order' => 40,
                'ar' => ['name' => 'فيتامين د'],
                'en' => ['name' => 'Vitamin D'],
            ],
            [
                'key' => 'vitamin_b12',
                'category' => 'Vitamins & Minerals',
                'unit' => 'pg/mL',
                'normal_range_min' => 200.0,
                'normal_range_max' => 900.0,
                'order' => 41,
                'ar' => ['name' => 'فيتامين ب12'],
                'en' => ['name' => 'Vitamin B12'],
            ],
            [
                'key' => 'iron',
                'category' => 'Vitamins & Minerals',
                'unit' => 'μg/dL',
                'normal_range_min' => 60.0,
                'normal_range_max' => 170.0,
                'order' => 42,
                'ar' => ['name' => 'الحديد'],
                'en' => ['name' => 'Iron'],
            ],

            // Urine Tests
            [
                'key' => 'urine_analysis',
                'category' => 'Urinalysis',
                'unit' => null,
                'normal_range_text' => 'Various parameters',
                'order' => 50,
                'ar' => ['name' => 'تحليل البول'],
                'en' => ['name' => 'Urine Analysis'],
            ],
        ];

        foreach ($tests as $test) {
            $translations = [
                'ar' => $test['ar'],
                'en' => $test['en'],
            ];
            unset($test['ar'], $test['en']);
            
            LabTestType::create(array_merge($test, $translations));
        }
    }
}
