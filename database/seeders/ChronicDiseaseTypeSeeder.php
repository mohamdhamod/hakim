<?php

namespace Database\Seeders;

use App\Models\ChronicDiseaseType;
use Illuminate\Database\Seeder;

class ChronicDiseaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diseases = [
            // Diabetes
            [
                'key' => 'diabetes_type1',
                'icd11_code' => '5A10',
                'category' => 'Endocrine',
                'followup_interval_days' => 90,
                'ar' => [
                    'name' => 'السكري من النوع الأول',
                    'description' => 'السكري المعتمد على الأنسولين',
                    'management_guidelines' => 'مراقبة السكر بانتظام، العلاج بالأنسولين، التحكم بالنظام الغذائي',
                ],
                'en' => [
                    'name' => 'Type 1 Diabetes',
                    'description' => 'Insulin-dependent diabetes mellitus',
                    'management_guidelines' => 'Regular blood glucose monitoring, insulin therapy, diet control',
                ],
            ],
            [
                'key' => 'diabetes_type2',
                'icd11_code' => '5A11',
                'category' => 'Endocrine',
                'followup_interval_days' => 90,
                'ar' => [
                    'name' => 'السكري من النوع الثاني',
                    'description' => 'السكري غير المعتمد على الأنسولين',
                    'management_guidelines' => 'التحكم بالنظام الغذائي، التمارين، الأدوية الفموية، الأنسولين عند الحاجة',
                ],
                'en' => [
                    'name' => 'Type 2 Diabetes',
                    'description' => 'Non-insulin-dependent diabetes mellitus',
                    'management_guidelines' => 'Diet control, exercise, oral medications, possible insulin',
                ],
            ],

            // Hypertension
            [
                'key' => 'hypertension',
                'icd11_code' => 'BA00',
                'category' => 'Cardiovascular',
                'followup_interval_days' => 60,
                'ar' => [
                    'name' => 'ارتفاع ضغط الدم الأساسي',
                    'description' => 'ارتفاع ضغط الدم بدون سبب معروف',
                    'management_guidelines' => 'مراقبة الضغط بانتظام، حمية قليلة الصوديوم، الأدوية، التمارين',
                ],
                'en' => [
                    'name' => 'Essential Hypertension',
                    'description' => 'High blood pressure without known cause',
                    'management_guidelines' => 'Regular BP monitoring, low sodium diet, medications, exercise',
                ],
            ],

            // Asthma
            [
                'key' => 'asthma',
                'icd11_code' => 'CA23',
                'category' => 'Respiratory',
                'followup_interval_days' => 180,
                'ar' => [
                    'name' => 'الربو القصبي',
                    'description' => 'مرض التهابي مزمن في المجاري التنفسية',
                    'management_guidelines' => 'تجنب المحفزات، استخدام البخاخات، مراقبة ذروة التدفق',
                ],
                'en' => [
                    'name' => 'Bronchial Asthma',
                    'description' => 'Chronic inflammatory disease of airways',
                    'management_guidelines' => 'Avoid triggers, inhaler use, peak flow monitoring',
                ],
            ],

            // COPD
            [
                'key' => 'copd',
                'icd11_code' => 'CA22',
                'category' => 'Respiratory',
                'followup_interval_days' => 90,
                'ar' => [
                    'name' => 'مرض الانسداد الرئوي المزمن',
                    'description' => 'مرض رئوي تقدمي',
                    'management_guidelines' => 'الإقلاع عن التدخين، موسعات القصبات، إعادة التأهيل الرئوي',
                ],
                'en' => [
                    'name' => 'Chronic Obstructive Pulmonary Disease (COPD)',
                    'description' => 'Progressive lung disease',
                    'management_guidelines' => 'Stop smoking, bronchodilators, pulmonary rehabilitation',
                ],
            ],

            // Heart Disease
            [
                'key' => 'cad',
                'icd11_code' => 'BA80',
                'category' => 'Cardiovascular',
                'followup_interval_days' => 90,
                'ar' => [
                    'name' => 'مرض الشريان التاجي',
                    'description' => 'تضيق أو انسداد الشرايين التاجية',
                    'management_guidelines' => 'الأدوية، تغيير نمط الحياة، إجراءات طبية عند الحاجة',
                ],
                'en' => [
                    'name' => 'Coronary Artery Disease',
                    'description' => 'Narrowing or blockage of coronary arteries',
                    'management_guidelines' => 'Medications, lifestyle changes, possible procedures',
                ],
            ],
            [
                'key' => 'heart_failure',
                'icd11_code' => 'BD10',
                'category' => 'Cardiovascular',
                'followup_interval_days' => 60,
                'ar' => [
                    'name' => 'فشل القلب',
                    'description' => 'عدم قدرة القلب على ضخ الدم بشكل كاف',
                    'management_guidelines' => 'تقييد السوائل، الأدوية، المراقبة المنتظمة',
                ],
                'en' => [
                    'name' => 'Heart Failure',
                    'description' => 'Heart cannot pump blood adequately',
                    'management_guidelines' => 'Fluid restriction, medications, regular monitoring',
                ],
            ],

            // Kidney Disease
            [
                'key' => 'ckd',
                'icd11_code' => 'GB61',
                'category' => 'Renal',
                'followup_interval_days' => 90,
                'ar' => [
                    'name' => 'مرض الكلى المزمن',
                    'description' => 'فقدان تدريجي لوظائف الكلى',
                    'management_guidelines' => 'السيطرة على الضغط والسكري، تقييد البروتين، الفحوصات المنتظمة',
                ],
                'en' => [
                    'name' => 'Chronic Kidney Disease',
                    'description' => 'Progressive loss of kidney function',
                    'management_guidelines' => 'Control BP and diabetes, protein restriction, regular tests',
                ],
            ],

            // Thyroid
            [
                'key' => 'hypothyroidism',
                'icd11_code' => '5A00',
                'category' => 'Endocrine',
                'followup_interval_days' => 180,
                'ar' => [
                    'name' => 'قصور الغدة الدرقية',
                    'description' => 'خمول الغدة الدرقية',
                    'management_guidelines' => 'العلاج الهرموني التعويضي، مراقبة TSH بانتظام',
                ],
                'en' => [
                    'name' => 'Hypothyroidism',
                    'description' => 'Underactive thyroid gland',
                    'management_guidelines' => 'Thyroid hormone replacement, regular TSH monitoring',
                ],
            ],
            [
                'key' => 'hyperthyroidism',
                'icd11_code' => '5A02',
                'category' => 'Endocrine',
                'followup_interval_days' => 90,
                'ar' => [
                    'name' => 'فرط نشاط الغدة الدرقية',
                    'description' => 'فرط نشاط الغدة الدرقية',
                    'management_guidelines' => 'أدوية مضادة للدرقية، اليود المشع أو الجراحة عند الحاجة',
                ],
                'en' => [
                    'name' => 'Hyperthyroidism',
                    'description' => 'Overactive thyroid gland',
                    'management_guidelines' => 'Anti-thyroid medications, possible radioiodine or surgery',
                ],
            ],

            // Arthritis
            [
                'key' => 'rheumatoid_arthritis',
                'icd11_code' => 'FA20',
                'category' => 'Rheumatologic',
                'followup_interval_days' => 90,
                'ar' => [
                    'name' => 'التهاب المفاصل الروماتويدي',
                    'description' => 'التهاب المفاصل المناعي الذاتي',
                    'management_guidelines' => 'الأدوية المعدلة للمرض، مضادات الالتهاب، العلاج الطبيعي',
                ],
                'en' => [
                    'name' => 'Rheumatoid Arthritis',
                    'description' => 'Autoimmune inflammatory arthritis',
                    'management_guidelines' => 'DMARDs, NSAIDs, physical therapy',
                ],
            ],
            [
                'key' => 'osteoarthritis',
                'icd11_code' => 'FA00',
                'category' => 'Rheumatologic',
                'followup_interval_days' => 180,
                'ar' => [
                    'name' => 'خشونة المفاصل',
                    'description' => 'مرض المفاصل التنكسي',
                    'management_guidelines' => 'إدارة الألم، التمارين، التحكم بالوزن، الجراحة عند الحاجة',
                ],
                'en' => [
                    'name' => 'Osteoarthritis',
                    'description' => 'Degenerative joint disease',
                    'management_guidelines' => 'Pain management, exercise, weight control, possible surgery',
                ],
            ],

            // Epilepsy
            [
                'key' => 'epilepsy',
                'icd11_code' => '8A60',
                'category' => 'Neurologic',
                'followup_interval_days' => 180,
                'ar' => [
                    'name' => 'الصرع',
                    'description' => 'نوبات تشنج متكررة',
                    'management_guidelines' => 'أدوية مضادة للصرع، تجنب المحفزات، تخطيط الدماغ المنتظم',
                ],
                'en' => [
                    'name' => 'Epilepsy',
                    'description' => 'Recurrent seizures',
                    'management_guidelines' => 'Anti-epileptic medications, avoid triggers, regular EEG',
                ],
            ],

            // Depression
            [
                'key' => 'major_depression',
                'icd11_code' => '6A70',
                'category' => 'Psychiatric',
                'followup_interval_days' => 60,
                'ar' => [
                    'name' => 'اضطراب الاكتئاب الشديد',
                    'description' => 'مزاج منخفض مستمر وفقدان الاهتمام',
                    'management_guidelines' => 'مضادات الاكتئاب، العلاج النفسي، تغيير نمط الحياة',
                ],
                'en' => [
                    'name' => 'Major Depressive Disorder',
                    'description' => 'Persistent low mood and loss of interest',
                    'management_guidelines' => 'Antidepressants, psychotherapy, lifestyle changes',
                ],
            ],
        ];

        foreach ($diseases as $disease) {
            $translations = [
                'ar' => $disease['ar'],
                'en' => $disease['en'],
            ];
            unset($disease['ar'], $disease['en']);
            
            ChronicDiseaseType::create(array_merge($disease, $translations));
        }
    }
}
