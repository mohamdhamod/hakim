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
                'name_en' => 'Complete Blood Count (CBC)',
                'name_ar' => 'تعداد الدم الشامل',
                'description_en' => 'Measures various components of blood',
                'description_ar' => 'قياس مكونات الدم المختلفة',
                'category' => 'Hematology',
                'unit' => null,
                'normal_range_text' => 'Various parameters',
                'order' => 1,
            ],
            [
                'name_en' => 'Hemoglobin (Hb)',
                'name_ar' => 'الهيموجلوبين',
                'category' => 'Hematology',
                'unit' => 'g/dL',
                'normal_range_min' => 12.0,
                'normal_range_max' => 16.0,
                'order' => 2,
            ],
            [
                'name_en' => 'White Blood Cells (WBC)',
                'name_ar' => 'كريات الدم البيضاء',
                'category' => 'Hematology',
                'unit' => '10^3/μL',
                'normal_range_min' => 4.0,
                'normal_range_max' => 11.0,
                'order' => 3,
            ],
            [
                'name_en' => 'Platelets',
                'name_ar' => 'الصفائح الدموية',
                'category' => 'Hematology',
                'unit' => '10^3/μL',
                'normal_range_min' => 150.0,
                'normal_range_max' => 400.0,
                'order' => 4,
            ],

            // Biochemistry
            [
                'name_en' => 'Blood Glucose (Fasting)',
                'name_ar' => 'سكر الدم الصائم',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 70.0,
                'normal_range_max' => 100.0,
                'order' => 10,
            ],
            [
                'name_en' => 'HbA1c (Glycated Hemoglobin)',
                'name_ar' => 'الهيموجلوبين السكري',
                'category' => 'Biochemistry',
                'unit' => '%',
                'normal_range_min' => 4.0,
                'normal_range_max' => 5.6,
                'order' => 11,
            ],
            [
                'name_en' => 'Cholesterol (Total)',
                'name_ar' => 'الكوليسترول الكلي',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 0,
                'normal_range_max' => 200.0,
                'order' => 12,
            ],
            [
                'name_en' => 'LDL Cholesterol',
                'name_ar' => 'الكوليسترول الضار',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 0,
                'normal_range_max' => 100.0,
                'order' => 13,
            ],
            [
                'name_en' => 'HDL Cholesterol',
                'name_ar' => 'الكوليسترول النافع',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 40.0,
                'normal_range_max' => 200.0,
                'order' => 14,
            ],
            [
                'name_en' => 'Triglycerides',
                'name_ar' => 'الدهون الثلاثية',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 0,
                'normal_range_max' => 150.0,
                'order' => 15,
            ],
            [
                'name_en' => 'Creatinine',
                'name_ar' => 'الكرياتينين',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 0.6,
                'normal_range_max' => 1.2,
                'order' => 16,
            ],
            [
                'name_en' => 'Urea/BUN',
                'name_ar' => 'اليوريا',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'normal_range_min' => 7.0,
                'normal_range_max' => 20.0,
                'order' => 17,
            ],

            // Liver Function
            [
                'name_en' => 'ALT (SGPT)',
                'name_ar' => 'إنزيم الكبد ALT',
                'category' => 'Liver Function',
                'unit' => 'U/L',
                'normal_range_min' => 7.0,
                'normal_range_max' => 55.0,
                'order' => 20,
            ],
            [
                'name_en' => 'AST (SGOT)',
                'name_ar' => 'إنزيم الكبد AST',
                'category' => 'Liver Function',
                'unit' => 'U/L',
                'normal_range_min' => 8.0,
                'normal_range_max' => 48.0,
                'order' => 21,
            ],
            [
                'name_en' => 'Total Bilirubin',
                'name_ar' => 'البيليروبين الكلي',
                'category' => 'Liver Function',
                'unit' => 'mg/dL',
                'normal_range_min' => 0.1,
                'normal_range_max' => 1.2,
                'order' => 22,
            ],

            // Thyroid Function
            [
                'name_en' => 'TSH',
                'name_ar' => 'هرمون الغدة الدرقية',
                'category' => 'Thyroid Function',
                'unit' => 'mIU/L',
                'normal_range_min' => 0.4,
                'normal_range_max' => 4.0,
                'order' => 30,
            ],
            [
                'name_en' => 'Free T4',
                'name_ar' => 'هرمون T4 الحر',
                'category' => 'Thyroid Function',
                'unit' => 'ng/dL',
                'normal_range_min' => 0.8,
                'normal_range_max' => 1.8,
                'order' => 31,
            ],

            // Vitamins & Minerals
            [
                'name_en' => 'Vitamin D',
                'name_ar' => 'فيتامين د',
                'category' => 'Vitamins & Minerals',
                'unit' => 'ng/mL',
                'normal_range_min' => 30.0,
                'normal_range_max' => 100.0,
                'order' => 40,
            ],
            [
                'name_en' => 'Vitamin B12',
                'name_ar' => 'فيتامين ب12',
                'category' => 'Vitamins & Minerals',
                'unit' => 'pg/mL',
                'normal_range_min' => 200.0,
                'normal_range_max' => 900.0,
                'order' => 41,
            ],
            [
                'name_en' => 'Iron',
                'name_ar' => 'الحديد',
                'category' => 'Vitamins & Minerals',
                'unit' => 'μg/dL',
                'normal_range_min' => 60.0,
                'normal_range_max' => 170.0,
                'order' => 42,
            ],

            // Urine Tests
            [
                'name_en' => 'Urine Analysis',
                'name_ar' => 'تحليل البول',
                'category' => 'Urinalysis',
                'unit' => null,
                'normal_range_text' => 'Various parameters',
                'order' => 50,
            ],
        ];

        foreach ($tests as $test) {
            LabTestType::create($test);
        }
    }
}
