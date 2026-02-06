<?php

namespace Database\Seeders;

use App\Models\VaccinationType;
use Illuminate\Database\Seeder;

class VaccinationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaccinations = [
            // Birth
            [
                'name_en' => 'BCG (Tuberculosis)',
                'name_ar' => 'لقاح السل',
                'description_en' => 'Bacillus Calmette-Guérin vaccine',
                'description_ar' => 'لقاح السل BCG',
                'disease_prevented' => 'Tuberculosis',
                'recommended_age_months' => 0,
                'age_group' => 'Newborn',
                'doses_required' => 1,
                'is_mandatory' => true,
                'order' => 1,
            ],
            [
                'name_en' => 'Hepatitis B (Birth Dose)',
                'name_ar' => 'التهاب الكبد ب (عند الولادة)',
                'disease_prevented' => 'Hepatitis B',
                'recommended_age_months' => 0,
                'age_group' => 'Newborn',
                'doses_required' => 1,
                'is_mandatory' => true,
                'order' => 2,
            ],

            // 2 Months
            [
                'name_en' => 'Pentavalent (DPT-HepB-Hib)',
                'name_ar' => 'اللقاح الخماسي',
                'description_en' => 'Diphtheria, Pertussis, Tetanus, Hepatitis B, Haemophilus influenzae type b',
                'description_ar' => 'الدفتيريا، السعال الديكي، الكزاز، التهاب الكبد ب، المستدمية النزلية',
                'disease_prevented' => 'Multiple diseases',
                'recommended_age_months' => 2,
                'age_group' => 'Infant',
                'doses_required' => 3,
                'interval_days' => 60,
                'is_mandatory' => true,
                'order' => 3,
            ],
            [
                'name_en' => 'Polio (OPV)',
                'name_ar' => 'شلل الأطفال (الفموي)',
                'disease_prevented' => 'Poliomyelitis',
                'recommended_age_months' => 2,
                'age_group' => 'Infant',
                'doses_required' => 3,
                'interval_days' => 60,
                'is_mandatory' => true,
                'order' => 4,
            ],
            [
                'name_en' => 'Pneumococcal (PCV)',
                'name_ar' => 'المكورات الرئوية',
                'disease_prevented' => 'Pneumococcal diseases',
                'recommended_age_months' => 2,
                'age_group' => 'Infant',
                'doses_required' => 3,
                'interval_days' => 60,
                'is_mandatory' => true,
                'order' => 5,
            ],
            [
                'name_en' => 'Rotavirus',
                'name_ar' => 'الفيروسة العجلية',
                'disease_prevented' => 'Rotavirus gastroenteritis',
                'recommended_age_months' => 2,
                'age_group' => 'Infant',
                'doses_required' => 2,
                'interval_days' => 60,
                'is_mandatory' => false,
                'order' => 6,
            ],

            // 6 Months
            [
                'name_en' => 'Hepatitis A',
                'name_ar' => 'التهاب الكبد أ',
                'disease_prevented' => 'Hepatitis A',
                'recommended_age_months' => 12,
                'age_group' => 'Child',
                'doses_required' => 2,
                'interval_days' => 180,
                'is_mandatory' => false,
                'order' => 10,
            ],

            // 9 Months
            [
                'name_en' => 'MMR (Measles, Mumps, Rubella)',
                'name_ar' => 'الحصبة والنكاف والحصبة الألمانية',
                'disease_prevented' => 'Measles, Mumps, Rubella',
                'recommended_age_months' => 12,
                'age_group' => 'Child',
                'doses_required' => 2,
                'interval_days' => 1095, // 3 years
                'is_mandatory' => true,
                'order' => 11,
            ],
            [
                'name_en' => 'Varicella (Chickenpox)',
                'name_ar' => 'جدري الماء',
                'disease_prevented' => 'Chickenpox',
                'recommended_age_months' => 12,
                'age_group' => 'Child',
                'doses_required' => 2,
                'interval_days' => 1095,
                'is_mandatory' => false,
                'order' => 12,
            ],

            // 18 Months Boosters
            [
                'name_en' => 'DPT Booster',
                'name_ar' => 'جرعة منشطة ثلاثي',
                'disease_prevented' => 'Diphtheria, Pertussis, Tetanus',
                'recommended_age_months' => 18,
                'age_group' => 'Toddler',
                'doses_required' => 1,
                'is_mandatory' => true,
                'order' => 20,
            ],
            [
                'name_en' => 'Polio Booster',
                'name_ar' => 'جرعة منشطة شلل الأطفال',
                'disease_prevented' => 'Poliomyelitis',
                'recommended_age_months' => 18,
                'age_group' => 'Toddler',
                'doses_required' => 1,
                'is_mandatory' => true,
                'order' => 21,
            ],

            // School Age
            [
                'name_en' => 'Tdap (Tetanus-Diphtheria-Pertussis)',
                'name_ar' => 'الكزاز والدفتيريا والسعال الديكي',
                'disease_prevented' => 'Tetanus, Diphtheria, Pertussis',
                'recommended_age_months' => 120, // 10 years
                'age_group' => 'Adolescent',
                'doses_required' => 1,
                'booster_after_months' => 120, // Every 10 years
                'is_mandatory' => true,
                'order' => 30,
            ],
            [
                'name_en' => 'HPV (Human Papillomavirus)',
                'name_ar' => 'فيروس الورم الحليمي البشري',
                'description_en' => 'Recommended for girls and boys',
                'description_ar' => 'موصى به للبنات والأولاد',
                'disease_prevented' => 'HPV-related cancers',
                'recommended_age_months' => 132, // 11 years
                'age_group' => 'Adolescent',
                'doses_required' => 2,
                'interval_days' => 180,
                'is_mandatory' => false,
                'order' => 31,
            ],

            // Adult Vaccines
            [
                'name_en' => 'Influenza (Annual)',
                'name_ar' => 'الإنفلونزا (سنوي)',
                'disease_prevented' => 'Influenza',
                'recommended_age_months' => 6,
                'age_group' => 'All Ages',
                'doses_required' => 1,
                'booster_after_months' => 12, // Annual
                'is_mandatory' => false,
                'order' => 40,
            ],
            [
                'name_en' => 'COVID-19',
                'name_ar' => 'كوفيد-19',
                'disease_prevented' => 'COVID-19',
                'recommended_age_months' => 72, // 6 years and above
                'age_group' => 'All Ages',
                'doses_required' => 2,
                'interval_days' => 21,
                'booster_after_months' => 6,
                'is_mandatory' => false,
                'order' => 41,
            ],
        ];

        foreach ($vaccinations as $vaccination) {
            VaccinationType::create($vaccination);
        }
    }
}
