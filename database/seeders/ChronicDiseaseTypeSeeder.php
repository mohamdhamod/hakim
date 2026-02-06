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
                'name_en' => 'Type 1 Diabetes',
                'name_ar' => 'السكري من النوع الأول',
                'description_en' => 'Insulin-dependent diabetes mellitus',
                'description_ar' => 'السكري المعتمد على الأنسولين',
                'icd11_code' => '5A10',
                'category' => 'Endocrine',
                'management_guidelines_en' => 'Regular blood glucose monitoring, insulin therapy, diet control',
                'management_guidelines_ar' => 'مراقبة السكر بانتظام، العلاج بالأنسولين، التحكم بالنظام الغذائي',
                'followup_interval_days' => 90,
            ],
            [
                'name_en' => 'Type 2 Diabetes',
                'name_ar' => 'السكري من النوع الثاني',
                'description_en' => 'Non-insulin-dependent diabetes mellitus',
                'description_ar' => 'السكري غير المعتمد على الأنسولين',
                'icd11_code' => '5A11',
                'category' => 'Endocrine',
                'management_guidelines_en' => 'Diet control, exercise, oral medications, possible insulin',
                'management_guidelines_ar' => 'التحكم بالنظام الغذائي، التمارين، الأدوية الفموية، الأنسولين عند الحاجة',
                'followup_interval_days' => 90,
            ],

            // Hypertension
            [
                'name_en' => 'Essential Hypertension',
                'name_ar' => 'ارتفاع ضغط الدم الأساسي',
                'description_en' => 'High blood pressure without known cause',
                'description_ar' => 'ارتفاع ضغط الدم بدون سبب معروف',
                'icd11_code' => 'BA00',
                'category' => 'Cardiovascular',
                'management_guidelines_en' => 'Regular BP monitoring, low sodium diet, medications, exercise',
                'management_guidelines_ar' => 'مراقبة الضغط بانتظام، حمية قليلة الصوديوم، الأدوية، التمارين',
                'followup_interval_days' => 60,
            ],

            // Asthma
            [
                'name_en' => 'Bronchial Asthma',
                'name_ar' => 'الربو القصبي',
                'description_en' => 'Chronic inflammatory disease of airways',
                'description_ar' => 'مرض التهابي مزمن في المجاري التنفسية',
                'icd11_code' => 'CA23',
                'category' => 'Respiratory',
                'management_guidelines_en' => 'Avoid triggers, inhaler use, peak flow monitoring',
                'management_guidelines_ar' => 'تجنب المحفزات، استخدام البخاخات، مراقبة ذروة التدفق',
                'followup_interval_days' => 180,
            ],

            // COPD
            [
                'name_en' => 'Chronic Obstructive Pulmonary Disease (COPD)',
                'name_ar' => 'مرض الانسداد الرئوي المزمن',
                'description_en' => 'Progressive lung disease',
                'description_ar' => 'مرض رئوي تقدمي',
                'icd11_code' => 'CA22',
                'category' => 'Respiratory',
                'management_guidelines_en' => 'Stop smoking, bronchodilators, pulmonary rehabilitation',
                'management_guidelines_ar' => 'الإقلاع عن التدخين، موسعات القصبات، إعادة التأهيل الرئوي',
                'followup_interval_days' => 90,
            ],

            // Heart Disease
            [
                'name_en' => 'Coronary Artery Disease',
                'name_ar' => 'مرض الشريان التاجي',
                'description_en' => 'Narrowing or blockage of coronary arteries',
                'description_ar' => 'تضيق أو انسداد الشرايين التاجية',
                'icd11_code' => 'BA80',
                'category' => 'Cardiovascular',
                'management_guidelines_en' => 'Medications, lifestyle changes, possible procedures',
                'management_guidelines_ar' => 'الأدوية، تغيير نمط الحياة، إجراءات طبية عند الحاجة',
                'followup_interval_days' => 90,
            ],
            [
                'name_en' => 'Heart Failure',
                'name_ar' => 'فشل القلب',
                'description_en' => 'Heart cannot pump blood adequately',
                'description_ar' => 'عدم قدرة القلب على ضخ الدم بشكل كاف',
                'icd11_code' => 'BD10',
                'category' => 'Cardiovascular',
                'management_guidelines_en' => 'Fluid restriction, medications, regular monitoring',
                'management_guidelines_ar' => 'تقييد السوائل، الأدوية، المراقبة المنتظمة',
                'followup_interval_days' => 60,
            ],

            // Kidney Disease
            [
                'name_en' => 'Chronic Kidney Disease',
                'name_ar' => 'مرض الكلى المزمن',
                'description_en' => 'Progressive loss of kidney function',
                'description_ar' => 'فقدان تدريجي لوظائف الكلى',
                'icd11_code' => 'GB61',
                'category' => 'Renal',
                'management_guidelines_en' => 'Control BP and diabetes, protein restriction, regular tests',
                'management_guidelines_ar' => 'السيطرة على الضغط والسكري، تقييد البروتين، الفحوصات المنتظمة',
                'followup_interval_days' => 90,
            ],

            // Thyroid
            [
                'name_en' => 'Hypothyroidism',
                'name_ar' => 'قصور الغدة الدرقية',
                'description_en' => 'Underactive thyroid gland',
                'description_ar' => 'خمول الغدة الدرقية',
                'icd11_code' => '5A00',
                'category' => 'Endocrine',
                'management_guidelines_en' => 'Thyroid hormone replacement, regular TSH monitoring',
                'management_guidelines_ar' => 'العلاج الهرموني التعويضي، مراقبة TSH بانتظام',
                'followup_interval_days' => 180,
            ],
            [
                'name_en' => 'Hyperthyroidism',
                'name_ar' => 'فرط نشاط الغدة الدرقية',
                'description_en' => 'Overactive thyroid gland',
                'description_ar' => 'فرط نشاط الغدة الدرقية',
                'icd11_code' => '5A02',
                'category' => 'Endocrine',
                'management_guidelines_en' => 'Anti-thyroid medications, possible radioiodine or surgery',
                'management_guidelines_ar' => 'أدوية مضادة للدرقية، اليود المشع أو الجراحة عند الحاجة',
                'followup_interval_days' => 90,
            ],

            // Arthritis
            [
                'name_en' => 'Rheumatoid Arthritis',
                'name_ar' => 'التهاب المفاصل الروماتويدي',
                'description_en' => 'Autoimmune inflammatory arthritis',
                'description_ar' => 'التهاب المفاصل المناعي الذاتي',
                'icd11_code' => 'FA20',
                'category' => 'Rheumatologic',
                'management_guidelines_en' => 'DMARDs, NSAIDs, physical therapy',
                'management_guidelines_ar' => 'الأدوية المعدلة للمرض، مضادات الالتهاب، العلاج الطبيعي',
                'followup_interval_days' => 90,
            ],
            [
                'name_en' => 'Osteoarthritis',
                'name_ar' => 'خشونة المفاصل',
                'description_en' => 'Degenerative joint disease',
                'description_ar' => 'مرض المفاصل التنكسي',
                'icd11_code' => 'FA00',
                'category' => 'Rheumatologic',
                'management_guidelines_en' => 'Pain management, exercise, weight control, possible surgery',
                'management_guidelines_ar' => 'إدارة الألم، التمارين، التحكم بالوزن، الجراحة عند الحاجة',
                'followup_interval_days' => 180,
            ],

            // Epilepsy
            [
                'name_en' => 'Epilepsy',
                'name_ar' => 'الصرع',
                'description_en' => 'Recurrent seizures',
                'description_ar' => 'نوبات تشنج متكررة',
                'icd11_code' => '8A60',
                'category' => 'Neurologic',
                'management_guidelines_en' => 'Anti-epileptic medications, avoid triggers, regular EEG',
                'management_guidelines_ar' => 'أدوية مضادة للصرع، تجنب المحفزات، تخطيط الدماغ المنتظم',
                'followup_interval_days' => 180,
            ],

            // Depression
            [
                'name_en' => 'Major Depressive Disorder',
                'name_ar' => 'اضطراب الاكتئاب الشديد',
                'description_en' => 'Persistent low mood and loss of interest',
                'description_ar' => 'مزاج منخفض مستمر وفقدان الاهتمام',
                'icd11_code' => '6A70',
                'category' => 'Psychiatric',
                'management_guidelines_en' => 'Antidepressants, psychotherapy, lifestyle changes',
                'management_guidelines_ar' => 'مضادات الاكتئاب، العلاج النفسي، تغيير نمط الحياة',
                'followup_interval_days' => 60,
            ],
        ];

        foreach ($diseases as $disease) {
            ChronicDiseaseType::create($disease);
        }
    }
}
