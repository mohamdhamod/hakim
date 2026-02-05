<?php

namespace Database\Seeders;

use App\Enums\ConfigEnum;
use App\Models\ConfigTitle;
use Illuminate\Database\Seeder;


class ConfigTitleTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'title' => [
                    'en' => 'Copyright',
                    'ar' => 'حقوق النشر',
                    'fr' => 'Droits d\'auteur',
                    'es' => 'Derechos de autor',
                    'de' => 'Urheberrecht',
                ],
                'description' => [
                    'en' => 'Copyright: Hakim Clinics',
                    'ar' => 'حقوق النشر: منصة حكيم للعيادات',
                    'fr' => 'Copyright: Hakim Clinics',
                    'es' => 'Copyright: Hakim Clinics',
                    'de' => 'Copyright: Hakim Clinics',
                ],
                'page' => ConfigEnum::FOOTER,
                'key' => ConfigEnum::COPYRIGHT,
            ],

            // About Us page content
            [
                'id' => 2,
                'title' => [
                    'en' => 'About Us',
                    'ar' => 'من نحن',
                    'fr' => 'À propos de nous',
                    'es' => 'Sobre nosotros',
                    'de' => 'Über uns',
                ],
                'description' => [
                    'en' => 'Hakim Clinics helps patients find the right clinic and book appointments easily. Doctors can manage schedules and patient care in one place.',
                    'ar' => 'منصة حكيم تساعد المرضى على العثور على العيادة المناسبة وحجز المواعيد بسهولة، وتمكّن الأطباء من إدارة المواعيد والمرضى في مكان واحد.',
                    'fr' => 'Hakim Clinics helps patients find the right clinic and book appointments easily. Doctors can manage schedules and patient care in one place.',
                    'es' => 'Hakim Clinics helps patients find the right clinic and book appointments easily. Doctors can manage schedules and patient care in one place.',
                    'de' => 'Hakim Clinics helps patients find the right clinic and book appointments easily. Doctors can manage schedules and patient care in one place.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_HERO,
            ],
            [
                'id' => 3,
                'title' => [
                    'en' => 'About Hakim Clinics',
                    'ar' => 'عن منصة حكيم للعيادات',
                    'fr' => 'About Hakim Clinics',
                    'es' => 'About Hakim Clinics',
                    'de' => 'About Hakim Clinics',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_TITLE,
            ],
            [
                'id' => 4,
                'title' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'description' => [
                    'en' => 'We connect patients with trusted clinics and provide doctors with tools to manage appointments, patients, and examinations efficiently.',
                    'ar' => 'نربط المرضى بالعيادات الموثوقة ونوفر للأطباء أدوات لإدارة المواعيد والمرضى والفحوصات بكفاءة.',
                    'fr' => 'We connect patients with trusted clinics and provide doctors with tools to manage appointments, patients, and examinations efficiently.',
                    'es' => 'We connect patients with trusted clinics and provide doctors with tools to manage appointments, patients, and examinations efficiently.',
                    'de' => 'We connect patients with trusted clinics and provide doctors with tools to manage appointments, patients, and examinations efficiently.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_BODY_1,
            ],
            [
                'id' => 5,
                'title' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'description' => [
                    'en' => 'We believe every patient deserves a smooth booking experience and every clinic deserves simple, reliable management tools.',
                    'ar' => 'نؤمن أن كل مريض يستحق تجربة حجز سهلة وأن كل عيادة تستحق أدوات إدارة بسيطة وموثوقة.',
                    'fr' => 'We believe every patient deserves a smooth booking experience and every clinic deserves simple, reliable management tools.',
                    'es' => 'We believe every patient deserves a smooth booking experience and every clinic deserves simple, reliable management tools.',
                    'de' => 'We believe every patient deserves a smooth booking experience and every clinic deserves simple, reliable management tools.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_BODY_2,
            ],
            [
                'id' => 6,
                'title' => [
                    'en' => 'Clinic Booking & Management',
                    'ar' => 'حجز العيادات وإدارة المواعيد',
                    'fr' => 'Clinic Booking & Management',
                    'es' => 'Clinic Booking & Management',
                    'de' => 'Clinic Booking & Management',
                ],
                'description' => [
                    'en' => 'Discover clinics, book appointments, and manage care in one platform designed for patients and doctors.',
                    'ar' => 'اكتشف العيادات واحجز المواعيد وأدر الرعاية في منصة واحدة للمرضى والأطباء.',
                    'fr' => 'Discover clinics, book appointments, and manage care in one platform designed for patients and doctors.',
                    'es' => 'Discover clinics, book appointments, and manage care in one platform designed for patients and doctors.',
                    'de' => 'Discover clinics, book appointments, and manage care in one platform designed for patients and doctors.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_HIGHLIGHT,
            ],
            [
                'id' => 7,
                'title' => [
                    'en' => 'What We Offer',
                    'ar' => 'ماذا نقدم',
                    'fr' => 'Ce que nous offrons',
                    'es' => 'Lo que ofrecemos',
                    'de' => 'Was wir anbieten',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_OFFER_TITLE,
            ],
            [
                'id' => 8,
                'title' => [
                    'en' => 'Our Vision',
                    'ar' => 'رؤيتنا',
                    'fr' => 'Notre vision',
                    'es' => 'Nuestra visión',
                    'de' => 'Unsere Vision',
                ],
                'description' => [
                    'en' => 'To make healthcare access simpler for patients and more efficient for clinics.',
                    'ar' => 'تبسيط الوصول إلى الرعاية الصحية للمرضى وجعل إدارة العيادات أكثر كفاءة.',
                    'fr' => 'To make healthcare access simpler for patients and more efficient for clinics.',
                    'es' => 'To make healthcare access simpler for patients and more efficient for clinics.',
                    'de' => 'To make healthcare access simpler for patients and more efficient for clinics.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_VISION,
            ],
            [
                'id' => 9,
                'title' => [
                    'en' => 'Our Mission',
                    'ar' => 'رسالتنا',
                    'fr' => 'Notre mission',
                    'es' => 'Nuestra misión',
                    'de' => 'Unsere Mission',
                ],
                'description' => [
                    'en' => 'To provide a reliable booking experience with clear schedules, reminders, and patient‑centric communication.',
                    'ar' => 'تقديم تجربة حجز موثوقة مع جداول واضحة وتذكيرات وتواصل موجه للمرضى.',
                    'fr' => 'To provide a reliable booking experience with clear schedules, reminders, and patient‑centric communication.',
                    'es' => 'To provide a reliable booking experience with clear schedules, reminders, and patient‑centric communication.',
                    'de' => 'To provide a reliable booking experience with clear schedules, reminders, and patient‑centric communication.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_MISSION,
            ],
            [
                'id' => 10,
                'title' => [
                    'en' => 'Why Hakim Clinics?',
                    'ar' => 'لماذا منصة حكيم للعيادات؟',
                    'fr' => 'Why Hakim Clinics?',
                    'es' => 'Why Hakim Clinics?',
                    'de' => 'Why Hakim Clinics?',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_TITLE,
            ],
            [
                'id' => 11,
                'title' => [
                    'en' => 'Intuitive Interface',
                    'ar' => 'واجهة سهلة الاستخدام',
                    'fr' => 'Interface intuitive',
                    'es' => 'Interfaz intuitiva',
                    'de' => 'Intuitive Benutzeroberfläche',
                ],
                'description' => [
                    'en' => 'A clean, user-friendly interface that helps patients find clinics and book appointments quickly.',
                    'ar' => 'واجهة نظيفة وسهلة الاستخدام تساعد المرضى في العثور على العيادات وحجز المواعيد بسرعة.',
                    'fr' => 'Une interface claire et conviviale qui aide les patients à trouver des cliniques et à réserver rapidement.',
                    'es' => 'Una interfaz clara y fácil de usar que ayuda a los pacientes a encontrar clínicas y reservar rápidamente.',
                    'de' => 'Eine klare, benutzerfreundliche Oberfläche, die Patienten hilft, Kliniken zu finden und schnell Termine zu buchen.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_SIMPLE_UI,
            ],
            [
                'id' => 12,
                'title' => [
                    'en' => 'Specialty Coverage',
                    'ar' => 'تغطية تخصصات متعددة',
                    'fr' => 'Couverture des spécialités',
                    'es' => 'Cobertura de especialidades',
                    'de' => 'Fachgebiete-Abdeckung',
                ],
                'description' => [
                    'en' => 'Support for dentistry, dermatology, general practice, physiotherapy, and more.',
                    'ar' => 'دعم لطب الأسنان والأمراض الجلدية والطب العام والعلاج الطبيعي والمزيد.',
                    'fr' => 'Prise en charge de la dentisterie, la dermatologie, la médecine générale, la physiothérapie et plus.',
                    'es' => 'Soporte para odontología, dermatología, medicina general, fisioterapia y más.',
                    'de' => 'Unterstützung für Zahnmedizin, Dermatologie, Allgemeinmedizin, Physiotherapie und mehr.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_SMART_TOOLS,
            ],
            [
                'id' => 13,
                'title' => [
                    'en' => 'Professional Quality',
                    'ar' => 'جودة احترافية',
                    'fr' => 'Qualité professionnelle',
                    'es' => 'Calidad profesional',
                    'de' => 'Professionelle Qualität',
                ],
                'description' => [
                    'en' => 'A reliable experience for patients and clinics, with secure data handling and clear booking flows.',
                    'ar' => 'تجربة موثوقة للمرضى والعيادات مع حماية للبيانات وتدفق حجز واضح.',
                    'fr' => 'Une expérience fiable pour les patients et les cliniques avec une gestion sécurisée des données.',
                    'es' => 'Una experiencia fiable para pacientes y clínicas con manejo seguro de datos.',
                    'de' => 'Ein zuverlässiges Erlebnis für Patienten und Kliniken mit sicherer Datenverarbeitung.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_PRO_WITHOUT_COMPLEXITY,
            ],
            [
                'id' => 14,
                'title' => [
                    'en' => 'For Clinics of All Sizes',
                    'ar' => 'لجميع أحجام العيادات',
                    'fr' => 'Pour les cliniques de toutes tailles',
                    'es' => 'Para clínicas de todos los tamaños',
                    'de' => 'Für Praxen aller Größen',
                ],
                'description' => [
                    'en' => 'Whether you\'re a solo practitioner or a multi-location clinic, our platform scales to meet your content needs.',
                    'ar' => 'سواء كنت طبيبًا منفردًا أو عيادة متعددة الفروع، منصتنا تتكيف لتلبية احتياجاتك من المحتوى.',
                    'fr' => 'Que vous soyez un praticien solo ou une clinique multi-sites, notre plateforme s\'adapte à vos besoins en contenu.',
                    'es' => 'Ya sea que sea un profesional independiente o una clínica con múltiples ubicaciones, nuestra plataforma se adapta a sus necesidades de contenido.',
                    'de' => 'Ob Einzelpraxis oder Klinik mit mehreren Standorten — unsere Plattform passt sich Ihren Content-Bedürfnissen an.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_FOR_INDIVIDUALS_COMPANIES,
            ],
            [
                'id' => 15,
                'title' => [
                    'en' => 'Continuous Support',
                    'ar' => 'دعم مستمر',
                    'fr' => 'Support continu',
                    'es' => 'Soporte continuo',
                    'de' => 'Kontinuierlicher Support',
                ],
                'description' => [
                    'en' => 'Regular updates with new specialties, booking features, and language support. Our team is always here to help.',
                    'ar' => 'تحديثات منتظمة مع تخصصات جديدة وميزات للحجز ودعم لغات متعددة. فريقنا موجود دائمًا للمساعدة.',
                    'fr' => 'Mises à jour régulières avec de nouvelles spécialités, fonctionnalités de réservation et support linguistique. Notre équipe est toujours là pour vous aider.',
                    'es' => 'Actualizaciones regulares con nuevas especialidades, funciones de reserva y soporte de idiomas. Nuestro equipo siempre está aquí para ayudar.',
                    'de' => 'Regelmäßige Updates mit neuen Fachrichtungen, Termin-Funktionen und Sprachunterstützung. Unser Team ist immer für Sie da.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_CONTINUOUS_SUPPORT,
            ],
            [
                'id' => 16,
                'title' => [
                    'en' => 'Start Booking Today',
                    'ar' => 'ابدأ الحجز اليوم',
                    'fr' => 'Commencez à réserver aujourd\'hui',
                    'es' => 'Comience a reservar hoy',
                    'de' => 'Beginnen Sie heute mit der Terminbuchung',
                ],
                'description' => [
                    'en' => 'Select a specialty, choose a clinic, and book your appointment in minutes.',
                    'ar' => 'اختر التخصص والعيادة المناسبة واحجز موعدك خلال دقائق.',
                    'fr' => 'Sélectionnez une spécialité, choisissez une clinique et réservez votre rendez-vous en quelques minutes.',
                    'es' => 'Seleccione una especialidad, elija una clínica y reserve su cita en minutos.',
                    'de' => 'Wählen Sie eine Fachrichtung, eine Klinik und buchen Sie Ihren Termin in wenigen Minuten.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CTA,
            ],
            [
                'id' => 17,
                'title' => [
                    'en' => 'Get Started Free',
                    'ar' => 'ابدأ مجانًا',
                    'fr' => 'Commencez gratuitement',
                    'es' => 'Comience gratis',
                    'de' => 'Kostenlos starten',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CTA_BUTTON,
            ],
            [
                'id' => 18,
                'title' => [
                    'en' => 'Contact',
                    'ar' => 'التواصل',
                    'fr' => 'Contact',
                    'es' => 'Contacto',
                    'de' => 'Kontakt',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_TITLE,
            ],
            [
                'id' => 19,
                'title' => [
                    'en' => 'Phone',
                    'ar' => 'الهاتف',
                    'fr' => 'Téléphone',
                    'es' => 'Teléfono',
                    'de' => 'Telefon',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_PHONE_LABEL,
            ],
            [
                'id' => 20,
                'title' => [
                    'en' => 'Email',
                    'ar' => 'البريد الإلكتروني',
                    'fr' => 'E-mail',
                    'es' => 'Correo electrónico',
                    'de' => 'E-Mail',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_EMAIL_LABEL,
            ],
            [
                'id' => 21,
                'title' => [
                    'en' => 'Not available',
                    'ar' => 'غير متوفر',
                    'fr' => 'Non disponible',
                    'es' => 'No disponible',
                    'de' => 'Nicht verfügbar',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_NOT_AVAILABLE,
            ],
            [
                'id' => 22,
                'title' => [
                    'en' => 'Call by phone',
                    'ar' => 'اتصال هاتفي',
                    'fr' => 'Appeler par téléphone',
                    'es' => 'Llamar por teléfono',
                    'de' => 'Telefonisch anrufen',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_PHONE_ARIA,
            ],
            [
                'id' => 23,
                'title' => [
                    'en' => 'Send email',
                    'ar' => 'إرسال بريد إلكتروني',
                    'fr' => 'Envoyer un e-mail',
                    'es' => 'Enviar correo electrónico',
                    'de' => 'E-Mail senden',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_EMAIL_ARIA,
            ],
            [
                'id' => 24,
                'title' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية',
                    'fr' => 'Politique de confidentialité',
                    'es' => 'Política de privacidad',
                    'de' => 'Datenschutzrichtlinie',
                ],
                'description' => [
                    'en' => '<p>Hakim Clinics respects your privacy. We collect account, clinic, and appointment information to provide booking and management services.</p><p>We use your data to operate the platform, send notifications, and improve service. We protect data with appropriate security measures.</p><p>If you have questions, please contact support.</p>',
                    'ar' => '<p>منصة حكيم تحترم خصوصيتك. نجمع معلومات الحساب والعيادة والمواعيد لتقديم خدمات الحجز والإدارة.</p><p>نستخدم بياناتك لتشغيل المنصة وإرسال الإشعارات وتحسين الخدمة، ونحمي البيانات بإجراءات أمنية مناسبة.</p><p>للاستفسار تواصل معنا.</p>',
                    'fr' => '<p>Hakim Clinics respects your privacy. We collect account, clinic, and appointment information to provide booking and management services.</p><p>We use your data to operate the platform, send notifications, and improve service. We protect data with appropriate security measures.</p><p>If you have questions, please contact support.</p>',
                    'es' => '<p>Hakim Clinics respects your privacy. We collect account, clinic, and appointment information to provide booking and management services.</p><p>We use your data to operate the platform, send notifications, and improve service. We protect data with appropriate security measures.</p><p>If you have questions, please contact support.</p>',
                    'de' => '<p>Hakim Clinics respects your privacy. We collect account, clinic, and appointment information to provide booking and management services.</p><p>We use your data to operate the platform, send notifications, and improve service. We protect data with appropriate security measures.</p><p>If you have questions, please contact support.</p>',
                ],
                'page' => ConfigEnum::PRIVACY_POLICY,
                'key' => ConfigEnum::PRIVACY_POLICY,
            ],
            [
                'id' => 25,
                'title' => [
                    'en' => 'Terms, Conditions and Agreements',
                    'ar' => 'الشروط والأحكام والاتفاقيات',
                    'fr' => 'Termes, conditions et accords',
                    'es' => 'Términos, condiciones y acuerdos',
                    'de' => 'Allgemeine Geschäftsbedingungen',
                ],
                'description' => [
                    'en' => '<p>By using Hakim Clinics, you agree to these terms.</p><ul><li>Provide accurate account information.</li><li>Use the platform for legitimate booking and clinic management.</li><li>Handle appointment and patient data responsibly.</li></ul><p>We may update these terms as needed.</p>',
                    'ar' => '<p>باستخدام منصة حكيم، فإنك توافق على هذه الشروط.</p><ul><li>تقديم معلومات حساب دقيقة.</li><li>استخدام المنصة للحجز وإدارة العيادات بشكل مشروع.</li><li>التعامل مع بيانات المواعيد والمرضى بمسؤولية.</li></ul><p>قد نقوم بتحديث هذه الشروط عند الحاجة.</p>',
                    'fr' => '<p>By using Hakim Clinics, you agree to these terms.</p><ul><li>Provide accurate account information.</li><li>Use the platform for legitimate booking and clinic management.</li><li>Handle appointment and patient data responsibly.</li></ul><p>We may update these terms as needed.</p>',
                    'es' => '<p>By using Hakim Clinics, you agree to these terms.</p><ul><li>Provide accurate account information.</li><li>Use the platform for legitimate booking and clinic management.</li><li>Handle appointment and patient data responsibly.</li></ul><p>We may update these terms as needed.</p>',
                    'de' => '<p>By using Hakim Clinics, you agree to these terms.</p><ul><li>Provide accurate account information.</li><li>Use the platform for legitimate booking and clinic management.</li><li>Handle appointment and patient data responsibly.</li></ul><p>We may update these terms as needed.</p>',
                ],
                'page' => ConfigEnum::TERMS_CONDITIONS_AND_AGREEMENTS,
                'key' => ConfigEnum::TERMS_CONDITIONS_AND_AGREEMENTS,
            ],
        ];


        foreach ($data as $item) {
            $newService = ConfigTitle::updateOrCreate([
                'id' => $item['id'],
            ],[
                'page' => $item['page'],
                'key' => $item['key'],
            ]);
            foreach ($item['title'] as $locale => $translation) {
                $newService->translateOrNew($locale)->title = $translation;
            }
            foreach ($item['description'] as $locale => $translation) {
                $newService->translateOrNew($locale)->description = $translation;
            }
            $newService->save();
        }

    }
}

