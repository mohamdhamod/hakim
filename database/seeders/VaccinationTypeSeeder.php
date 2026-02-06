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
                'key' => 'bcg',
                'disease_prevented' => 'Tuberculosis',
                'recommended_age_months' => 0,
                'age_group' => 'Newborn',
                'doses_required' => 1,
                'is_mandatory' => true,
                'order' => 1,
                'ar' => [
                    'name' => 'لقاح السل',
                    'description' => 'لقاح السل BCG',
                ],
                'en' => [
                    'name' => 'BCG (Tuberculosis)',
                    'description' => 'Bacillus Calmette-Guérin vaccine',
                ],
            ],
            [
                'key' => 'hepb_birth',
                'disease_prevented' => 'Hepatitis B',
                'recommended_age_months' => 0,
                'age_group' => 'Newborn',
                'doses_required' => 1,
                'is_mandatory' => true,
                'order' => 2,
                'ar' => ['name' => 'التهاب الكبد ب (عند الولادة)'],
                'en' => ['name' => 'Hepatitis B (Birth Dose)'],
            ],

            // 2 Months
            [
                'key' => 'pentavalent',
                'disease_prevented' => 'Multiple diseases',
                'recommended_age_months' => 2,
                'age_group' => 'Infant',
                'doses_required' => 3,
                'interval_days' => 60,
                'is_mandatory' => true,
                'order' => 3,
                'ar' => [
                    'name' => 'اللقاح الخماسي',
                    'description' => 'الدفتيريا، السعال الديكي، الكزاز، التهاب الكبد ب، المستدمية النزلية',
                ],
                'en' => [
                    'name' => 'Pentavalent (DPT-HepB-Hib)',
                    'description' => 'Diphtheria, Pertussis, Tetanus, Hepatitis B, Haemophilus influenzae type b',
                ],
            ],
            [
                'key' => 'polio_opv',
                'disease_prevented' => 'Poliomyelitis',
                'recommended_age_months' => 2,
                'age_group' => 'Infant',
                'doses_required' => 3,
                'interval_days' => 60,
                'is_mandatory' => true,
                'order' => 4,
                'ar' => ['name' => 'شلل الأطفال (الفموي)'],
                'en' => ['name' => 'Polio (OPV)'],
            ],
            [
                'key' => 'pneumococcal',
                'disease_prevented' => 'Pneumococcal diseases',
                'recommended_age_months' => 2,
                'age_group' => 'Infant',
                'doses_required' => 3,
                'interval_days' => 60,
                'is_mandatory' => true,
                'order' => 5,
                'ar' => ['name' => 'المكورات الرئوية'],
                'en' => ['name' => 'Pneumococcal (PCV)'],
            ],
            [
                'key' => 'rotavirus',
                'disease_prevented' => 'Rotavirus gastroenteritis',
                'recommended_age_months' => 2,
                'age_group' => 'Infant',
                'doses_required' => 2,
                'interval_days' => 60,
                'is_mandatory' => false,
                'order' => 6,
                'ar' => ['name' => 'الفيروسة العجلية'],
                'en' => ['name' => 'Rotavirus'],
            ],

            // 6-12 Months
            [
                'key' => 'hepatitis_a',
                'disease_prevented' => 'Hepatitis A',
                'recommended_age_months' => 12,
                'age_group' => 'Child',
                'doses_required' => 2,
                'interval_days' => 180,
                'is_mandatory' => false,
                'order' => 10,
                'ar' => ['name' => 'التهاب الكبد أ'],
                'en' => ['name' => 'Hepatitis A'],
            ],

            // 9-12 Months
            [
                'key' => 'mmr',
                'disease_prevented' => 'Measles, Mumps, Rubella',
                'recommended_age_months' => 12,
                'age_group' => 'Child',
                'doses_required' => 2,
                'interval_days' => 1095, // 3 years
                'is_mandatory' => true,
                'order' => 11,
                'ar' => ['name' => 'الحصبة والنكاف والحصبة الألمانية'],
                'en' => ['name' => 'MMR (Measles, Mumps, Rubella)'],
            ],
            [
                'key' => 'varicella',
                'disease_prevented' => 'Chickenpox',
                'recommended_age_months' => 12,
                'age_group' => 'Child',
                'doses_required' => 2,
                'interval_days' => 1095,
                'is_mandatory' => false,
                'order' => 12,
                'ar' => ['name' => 'جدري الماء'],
                'en' => ['name' => 'Varicella (Chickenpox)'],
            ],

            // 18 Months Boosters
            [
                'key' => 'dpt_booster',
                'disease_prevented' => 'Diphtheria, Pertussis, Tetanus',
                'recommended_age_months' => 18,
                'age_group' => 'Toddler',
                'doses_required' => 1,
                'is_mandatory' => true,
                'order' => 20,
                'ar' => ['name' => 'جرعة منشطة ثلاثي'],
                'en' => ['name' => 'DPT Booster'],
            ],
            [
                'key' => 'polio_booster',
                'disease_prevented' => 'Poliomyelitis',
                'recommended_age_months' => 18,
                'age_group' => 'Toddler',
                'doses_required' => 1,
                'is_mandatory' => true,
                'order' => 21,
                'ar' => ['name' => 'جرعة منشطة شلل الأطفال'],
                'en' => ['name' => 'Polio Booster'],
            ],

            // School Age
            [
                'key' => 'tdap',
                'disease_prevented' => 'Tetanus, Diphtheria, Pertussis',
                'recommended_age_months' => 120, // 10 years
                'age_group' => 'Adolescent',
                'doses_required' => 1,
                'booster_after_months' => 120, // Every 10 years
                'is_mandatory' => true,
                'order' => 30,
                'ar' => ['name' => 'الكزاز والدفتيريا والسعال الديكي'],
                'en' => ['name' => 'Tdap (Tetanus-Diphtheria-Pertussis)'],
            ],
            [
                'key' => 'hpv',
                'disease_prevented' => 'HPV-related cancers',
                'recommended_age_months' => 132, // 11 years
                'age_group' => 'Adolescent',
                'doses_required' => 2,
                'interval_days' => 180,
                'is_mandatory' => false,
                'order' => 31,
                'ar' => [
                    'name' => 'فيروس الورم الحليمي البشري',
                    'description' => 'موصى به للبنات والأولاد',
                ],
                'en' => [
                    'name' => 'HPV (Human Papillomavirus)',
                    'description' => 'Recommended for girls and boys',
                ],
            ],

            // Adult Vaccines
            [
                'key' => 'influenza_annual',
                'disease_prevented' => 'Influenza',
                'recommended_age_months' => 6,
                'age_group' => 'All Ages',
                'doses_required' => 1,
                'booster_after_months' => 12, // Annual
                'is_mandatory' => false,
                'order' => 40,
                'ar' => ['name' => 'الإنفلونزا (سنوي)'],
                'en' => ['name' => 'Influenza (Annual)'],
            ],
            [
                'key' => 'covid19',
                'disease_prevented' => 'COVID-19',
                'recommended_age_months' => 72, // 6 years and above
                'age_group' => 'All Ages',
                'doses_required' => 2,
                'interval_days' => 21,
                'booster_after_months' => 6,
                'is_mandatory' => false,
                'order' => 41,
                'ar' => ['name' => 'كوفيد-19'],
                'en' => ['name' => 'COVID-19'],
            ],
        ];

        foreach ($vaccinations as $vaccination) {
            $translations = [
                'ar' => $vaccination['ar'],
                'en' => $vaccination['en'],
            ];
            unset($vaccination['ar'], $vaccination['en']);
            
            VaccinationType::create(array_merge($vaccination, $translations));
        }
    }
}
