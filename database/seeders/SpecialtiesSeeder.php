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
                    'de' => 'Zahnmedizin',
                    'es' => 'Odontología',
                    'fr' => 'Dentisterie',
                ],
                'description' => [
                    'en' => 'Dental care and oral health content',
                    'ar' => 'محتوى العناية بالأسنان وصحة الفم',
                    'de' => 'Zahnpflege und Mundgesundheit Inhalte',
                    'es' => 'Contenido de cuidado dental y salud bucal',
                    'fr' => 'Contenu sur les soins dentaires et la santé bucco-dentaire',
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
                    'de' => 'Dermatologie',
                    'es' => 'Dermatología',
                    'fr' => 'Dermatologie',
                ],
                'description' => [
                    'en' => 'Skin health and dermatological care content',
                    'ar' => 'محتوى صحة الجلد والرعاية الجلدية',
                    'de' => 'Hautgesundheit und dermatologische Pflege Inhalte',
                    'es' => 'Contenido de salud de la piel y cuidado dermatológico',
                    'fr' => 'Contenu sur la santé de la peau et les soins dermatologiques',
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
                    'de' => 'Allgemeinmedizin',
                    'es' => 'Clínica General',
                    'fr' => 'Clinique Générale',
                ],
                'description' => [
                    'en' => 'General healthcare and wellness content',
                    'ar' => 'محتوى الرعاية الصحية العامة والعافية',
                    'de' => 'Allgemeine Gesundheitsversorgung und Wellness Inhalte',
                    'es' => 'Contenido de atención médica general y bienestar',
                    'fr' => 'Contenu sur les soins de santé généraux et le bien-être',
                ],
                'icon' => 'fa-stethoscope',
                'color' => '#41B3A3',
                
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'key' => 'physiotherapy',
                'name' => [
                    'en' => 'Physiotherapy',
                    'ar' => 'العلاج الطبيعي',
                    'de' => 'Physiotherapie',
                    'es' => 'Fisioterapia',
                    'fr' => 'Physiothérapie',
                ],
                'description' => [
                    'en' => 'Physical therapy and rehabilitation content',
                    'ar' => 'محتوى العلاج الطبيعي وإعادة التأهيل',
                    'de' => 'Physiotherapie und Rehabilitationsinhalte',
                    'es' => 'Contenido de fisioterapia y rehabilitación',
                    'fr' => 'Contenu sur la physiothérapie et la réhabilitation',
                ],
                'icon' => 'fa-person-walking',
                'color' => '#9B59B6',
                
                'active' => true,
                'sort_order' => 4,
            ],
            [
                'key' => 'cardiology',
                'name' => [
                    'en' => 'Cardiology',
                    'ar' => 'أمراض القلب',
                    'de' => 'Kardiologie',
                    'es' => 'Cardiología',
                    'fr' => 'Cardiologie',
                ],
                'description' => [
                    'en' => 'Heart health and cardiovascular care content',
                    'ar' => 'محتوى صحة القلب والرعاية القلبية الوعائية',
                    'de' => 'Inhalte zur Herzgesundheit und kardiovaskulären Versorgung',
                    'es' => 'Contenido de salud cardíaca y cuidado cardiovascular',
                    'fr' => 'Contenu sur la santé cardiaque et les soins cardiovasculaires',
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
                    'de' => 'Pädiatrie',
                    'es' => 'Pediatría',
                    'fr' => 'Pédiatrie',
                ],
                'description' => [
                    'en' => 'Children health and pediatric care content',
                    'ar' => 'محتوى صحة الأطفال والرعاية الطبية للأطفال',
                    'de' => 'Inhalte zur Kindergesundheit und pädiatrischen Versorgung',
                    'es' => 'Contenido de salud infantil y atención pediátrica',
                    'fr' => 'Contenu sur la santé des enfants et les soins pédiatriques',
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
                    'de' => 'Augenheilkunde',
                    'es' => 'Oftalmología',
                    'fr' => 'Ophtalmologie',
                ],
                'description' => [
                    'en' => 'Eye health and vision care content',
                    'ar' => 'محتوى صحة العين ورعاية البصر',
                    'de' => 'Inhalte zur Augengesundheit und Sehpflege',
                    'es' => 'Contenido de salud ocular y cuidado de la visión',
                    'fr' => 'Contenu sur la santé des yeux et les soins de la vision',
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
                    'de' => 'Orthopädie',
                    'es' => 'Ortopedia',
                    'fr' => 'Orthopédie',
                ],
                'description' => [
                    'en' => 'Bone, joint, and musculoskeletal system care',
                    'ar' => 'رعاية العظام والمفاصل والجهاز العضلي الهيكلي',
                    'de' => 'Knochen-, Gelenk- und Bewegungsapparatversorgung',
                    'es' => 'Cuidado de huesos, articulaciones y sistema musculoesquelético',
                    'fr' => 'Soins des os, articulations et système musculo-squelettique',
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
                    'de' => 'HNO (Hals-Nasen-Ohren)',
                    'es' => 'Otorrinolaringología',
                    'fr' => 'ORL (Oto-Rhino-Laryngologie)',
                ],
                'description' => [
                    'en' => 'Ear, nose, throat, and related head and neck care',
                    'ar' => 'رعاية الأذن والأنف والحنجرة والرأس والعنق',
                    'de' => 'Hals-, Nasen-, Ohren- und Kopf-Hals-Versorgung',
                    'es' => 'Cuidado de oído, nariz, garganta y cabeza-cuello',
                    'fr' => 'Soins de l\'oreille, du nez, de la gorge et de la tête-cou',
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
                    'de' => 'Psychiatrie',
                    'es' => 'Psiquiatría',
                    'fr' => 'Psychiatrie',
                ],
                'description' => [
                    'en' => 'Mental health, emotional well-being, and psychiatric care',
                    'ar' => 'الصحة النفسية والعافية العاطفية والرعاية النفسية',
                    'de' => 'Psychische Gesundheit, emotionales Wohlbefinden und psychiatrische Versorgung',
                    'es' => 'Salud mental, bienestar emocional y atención psiquiátrica',
                    'fr' => 'Santé mentale, bien-être émotionnel et soins psychiatriques',
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
                    'de' => 'Neurologie',
                    'es' => 'Neurología',
                    'fr' => 'Neurologie',
                ],
                'description' => [
                    'en' => 'Brain, nervous system, and neurological disorders care',
                    'ar' => 'رعاية الدماغ والجهاز العصبي والاضطرابات العصبية',
                    'de' => 'Gehirn-, Nervensystem- und neurologische Störungsversorgung',
                    'es' => 'Cuidado del cerebro, sistema nervioso y trastornos neurológicos',
                    'fr' => 'Soins du cerveau, du système nerveux et des troubles neurologiques',
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
