<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClinicService;

class ClinicServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'key' => 'lab_tests',
                'icon' => 'fas fa-flask',
                'color' => '#17a2b8',
                'sort_order' => 1,
                'translations' => [
                    'en' => [
                        'name' => 'Lab Tests',
                        'description' => 'Laboratory tests and blood work',
                    ],
                    'ar' => [
                        'name' => 'التحاليل المخبرية',
                        'description' => 'الفحوصات المخبرية وتحاليل الدم',
                    ],
                ],
            ],
            [
                'key' => 'vaccinations',
                'icon' => 'fas fa-syringe',
                'color' => '#28a745',
                'sort_order' => 2,
                'translations' => [
                    'en' => [
                        'name' => 'Vaccinations',
                        'description' => 'Vaccination records and immunizations',
                    ],
                    'ar' => [
                        'name' => 'التطعيمات',
                        'description' => 'سجلات التطعيمات واللقاحات',
                    ],
                ],
            ],
            [
                'key' => 'growth_chart',
                'icon' => 'fas fa-chart-line',
                'color' => '#fd7e14',
                'sort_order' => 3,
                'translations' => [
                    'en' => [
                        'name' => 'Growth Chart',
                        'description' => 'Growth measurements and WHO charts',
                    ],
                    'ar' => [
                        'name' => 'منحنى النمو',
                        'description' => 'قياسات النمو ومنحنيات منظمة الصحة العالمية',
                    ],
                ],
            ],
            [
                'key' => 'chronic_diseases',
                'icon' => 'fas fa-heartbeat',
                'color' => '#dc3545',
                'sort_order' => 4,
                'translations' => [
                    'en' => [
                        'name' => 'Chronic Diseases',
                        'description' => 'Chronic disease management and monitoring',
                    ],
                    'ar' => [
                        'name' => 'الأمراض المزمنة',
                        'description' => 'إدارة ومتابعة الأمراض المزمنة',
                    ],
                ],
            ],
            [
                'key' => 'surgical_history',
                'icon' => 'fas fa-procedures',
                'color' => '#6f42c1',
                'sort_order' => 5,
                'translations' => [
                    'en' => [
                        'name' => 'Surgical History',
                        'description' => 'Surgical procedures and operations records',
                    ],
                    'ar' => [
                        'name' => 'التاريخ الجراحي',
                        'description' => 'سجلات العمليات الجراحية والإجراءات',
                    ],
                ],
            ],
            [
                'key' => 'problem_list',
                'icon' => 'fas fa-list-check',
                'color' => '#e83e8c',
                'sort_order' => 6,
                'translations' => [
                    'en' => [
                        'name' => 'Problem List',
                        'description' => 'Patient problems with ICD codes tracking',
                    ],
                    'ar' => [
                        'name' => 'قائمة المشاكل الصحية',
                        'description' => 'تتبع مشاكل المريض مع رموز ICD',
                    ],
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            $service = ClinicService::updateOrCreate(
                ['key' => $serviceData['key']],
                [
                    'icon' => $serviceData['icon'],
                    'color' => $serviceData['color'],
                    'sort_order' => $serviceData['sort_order'],
                    'active' => true,
                ]
            );

            foreach ($serviceData['translations'] as $locale => $translation) {
                $service->translateOrNew($locale)->name = $translation['name'];
                $service->translateOrNew($locale)->description = $translation['description'];
            }

            $service->save();
        }
    }
}
