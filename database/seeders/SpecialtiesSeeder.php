<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtiesSeeder extends Seeder
{
    /**
     * Seed the specialties.
     */
    public function run(): void
    {
        $specialties = [
            [
                'key' => 'dentistry',
                'name' => [
                    'en' => 'Dentistry',
                    'ar' => 'طب الأسنان',
                   
                ],
                'description' => [
                    'en' => 'Dental care and oral health content',
                    'ar' => 'محتوى العناية بالأسنان وصحة الفم',
                ],
                'icon' => 'fa-tooth',
                'color' => '#4A90D9',
                
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'key' => 'dermatology',
                'name' => [
                    'en' => 'Dermatology',
                    'ar' => 'الأمراض الجلدية',
                   
                ],
                'description' => [
                    'en' => 'Skin health and dermatological care content',
                    'ar' => 'محتوى صحة الجلد والرعاية الجلدية',
               ],
                'icon' => 'fa-hand-sparkles',
                'color' => '#E8A87C',
                
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'key' => 'general_clinic',
                'name' => [
                    'en' => 'General Clinic',
                    'ar' => 'العيادة العامة',
                
                ],
                'description' => [
                    'en' => 'General healthcare and wellness content',
                    'ar' => 'محتوى الرعاية الصحية العامة والعافية',
                ],
                'icon' => 'fa-stethoscope',
                'color' => '#41B3A3',
                
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'key' => 'cardiology',
                'name' => [
                    'en' => 'Cardiology',
                    'ar' => 'أمراض القلب',
                  
                ],
                'description' => [
                    'en' => 'Heart health and cardiovascular care content',
                    'ar' => 'محتوى صحة القلب والرعاية القلبية الوعائية',
               ],
                'icon' => 'fa-heart-pulse',
                'color' => '#E74C3C',
                
                'active' => true,
                'sort_order' => 5,
            ],
            [
                'key' => 'pediatrics',
                'name' => [
                    'en' => 'Pediatrics',
                    'ar' => 'طب الأطفال',
                 
                ],
                'description' => [
                    'en' => 'Children health and pediatric care content',
                    'ar' => 'محتوى صحة الأطفال والرعاية الطبية للأطفال',
                 ],
                'icon' => 'fa-baby',
                'color' => '#3498DB',
                
                'active' => true,
                'sort_order' => 6,
            ],
            [
                'key' => 'ophthalmology',
                'name' => [
                    'en' => 'Ophthalmology',
                    'ar' => 'طب العيون',
                    
                ],
                'description' => [
                    'en' => 'Eye health and vision care content',
                    'ar' => 'محتوى صحة العين ورعاية البصر',
               ],
                'icon' => 'fa-eye',
                'color' => '#2ECC71',
                
                'active' => true,
                'sort_order' => 7,
            ],
            [
                'key' => 'orthopedics',
                'name' => [
                    'en' => 'Orthopedics',
                    'ar' => 'جراحة العظام',
                  
                ],
                'description' => [
                    'en' => 'Bone, joint, and musculoskeletal system care',
                    'ar' => 'رعاية العظام والمفاصل والجهاز العضلي الهيكلي',
                ],
                'icon' => 'fa-bone',
                'color' => '#95A5A6',
                
                'active' => true,
                'sort_order' => 8,
            ],
            [
                'key' => 'ent',
                'name' => [
                    'en' => 'ENT (Ear, Nose, Throat)',
                    'ar' => 'الأنف والأذن والحنجرة',
                 ],
                'description' => [
                    'en' => 'Ear, nose, throat, and related head and neck care',
                    'ar' => 'رعاية الأذن والأنف والحنجرة والرأس والعنق',
              ],
                'icon' => 'fa-head-side-mask',
                'color' => '#E67E22',
                
                'active' => true,
                'sort_order' => 9,
            ],
            [
                'key' => 'psychiatry',
                'name' => [
                    'en' => 'Psychiatry',
                    'ar' => 'الطب النفسي',
                ],
                'description' => [
                    'en' => 'Mental health, emotional well-being, and psychiatric care',
                    'ar' => 'الصحة النفسية والعافية العاطفية والرعاية النفسية',
                ],
                'icon' => 'fa-brain',
                'color' => '#9B59B6',
                
                'active' => true,
                'sort_order' => 10,
            ],
            [
                'key' => 'neurology',
                'name' => [
                    'en' => 'Neurology',
                    'ar' => 'الأمراض العصبية',
               ],
                'description' => [
                    'en' => 'Brain, nervous system, and neurological disorders care',
                    'ar' => 'رعاية الدماغ والجهاز العصبي والاضطرابات العصبية',
               ],
                'icon' => 'fa-brain',
                'color' => '#8E44AD',
                
                'active' => true,
                'sort_order' => 11,
            ],
        ];

        foreach ($specialties as $specialtyData) {
            $translations = [
                'name' => $specialtyData['name'],
                'description' => $specialtyData['description'],
            ];
            $topicsData = $specialtyData['topics'] ?? [];
            unset($specialtyData['name'], $specialtyData['description'], $specialtyData['topics']);

            // Add slug from key
            $specialtyData['slug'] = $specialtyData['key'];

            $specialty = Specialty::updateOrCreate(
                ['key' => $specialtyData['key']],
                $specialtyData
            );

            foreach (array_keys(config('languages', [])) as $locale) {
                $specialty->translateOrNew($locale)->name = $translations['name'][$locale] ?? $translations['name']['en'];
                $specialty->translateOrNew($locale)->description = $translations['description'][$locale] ?? $translations['description']['en'];
            }

            $specialty->save();
        }
    }

}
