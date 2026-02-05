# إضافة حقل البلد وتحديد اللغة التلقائية

## ملخص التعديلات

تم إضافة ميزة حقل البلد للمستخدمين مع تحديد اللغة تلقائياً بناءً على بلد المستخدم عند أول تسجيل دخول.

## الملفات المضافة/المعدلة

### 1. Database Migration
- **الملف الجديد**: `database/migrations/2026_02_02_000001_add_country_id_to_users_table.php`
  - إضافة حقل `country_id` (foreign key) إلى جدول `users`
  - إضافة حقل `locale_auto_detected` (boolean) لتتبع ما إذا تم تحديد اللغة تلقائياً

### 2. User Model
- **الملف**: `app/Models/User.php`
  - إضافة `locale_auto_detected` إلى `$casts`
  - العلاقة `country()` موجودة مسبقاً

### 3. خدمة تحديد اللغة
- **الملف الجديد**: `app/Services/LocaleService.php`
  - `getLocaleByCountryCode()`: تحديد اللغة بناءً على رمز البلد
  - `getLocaleForUser()`: تحديد اللغة بناءً على معلومات المستخدم
  - `shouldAutoDetectLocale()`: التحقق مما إذا كان يجب تطبيق اللغة التلقائية
  - يحتوي على خريطة كاملة لتحويل رموز البلدان إلى اللغات المناسبة

### 4. Middleware
- **الملف الجديد**: `app/Http/Middleware/AutoDetectUserLocale.php`
  - يتم تشغيله بعد `LocaleFromUrl`
  - يحدد اللغة تلقائياً للمستخدم الجديد عند أول زيارة
  - يحدث `locale_auto_detected` إلى `true` بعد أول تحديد
- **الملف المعدل**: `bootstrap/app.php`
  - تسجيل الـ middleware الجديد

### 5. فورم التسجيل
- **الملف**: `resources/views/auth/register-complete.blade.php`
  - إضافة حقل اختيار البلد (required)
- **الملف**: `app/Actions/Fortify/CreateNewUser.php`
  - إضافة validation لـ `country_id`
  - حفظ `country_id` عند إنشاء المستخدم

### 6. فورم البروفايل
- **الملف**: `resources/views/auth/profile.blade.php`
  - إضافة حقل اختيار البلد (required)
- **الملف**: `app/Actions/Fortify/UpdateUserProfileInformation.php`
  - إضافة validation لـ `country_id`
  - تحديث `country_id` عند تحديث البروفايل

### 7. لوحة التحكم - إدارة المستخدمين
- **الملف**: `resources/views/dashboard/users/edit.blade.php`
  - إضافة حقل اختيار البلد
- **الملف**: `app/Http/Controllers/Dashboard/UsersController.php`
  - إضافة validation لـ `country_id` في `store()` و `update()`
  - حفظ/تحديث `country_id`

### 8. ملفات الترجمة
- **الملفات المعدلة**:
  - `resources/lang/ar/translation.php`
  - `resources/lang/en/translation.php`
  - `resources/lang/fr/translation.php`
  - إضافة مفاتيح الترجمة:
    - `auth.country`: "البلد" / "Country" / "Pays"
    - `auth.select_country`: "اختر البلد" / "Select Country" / "Sélectionner le pays"

## كيفية عمل النظام

### 1. عند التسجيل
1. المستخدم يختار البلد (إجباري)
2. يتم حفظ `country_id` في قاعدة البيانات
3. `locale_auto_detected` يتم تعيينه إلى `false`

### 2. عند أول تسجيل دخول
1. الـ middleware `AutoDetectUserLocale` يتحقق من:
   - هل المستخدم مسجل دخول؟
   - هل `locale_auto_detected = false`؟
   - هل لديه بلد محدد؟
2. إذا كانت جميع الشروط صحيحة:
   - يتم تحديد اللغة المناسبة بناءً على رمز البلد (مثلاً: SA → ar, FR → fr, US → en)
   - يتم تطبيق اللغة على الجلسة
   - يتم تحديث `locale_auto_detected` إلى `true`

### 3. الزيارات التالية
- لن يتم تغيير اللغة تلقائياً
- المستخدم يمكنه تغيير اللغة يدوياً من خلال زر تغيير اللغة
- اللغة المختارة يدوياً تبقى في الجلسة

### 4. تحديث البروفايل
- يمكن للمستخدم تغيير البلد في أي وقت
- تغيير البلد **لا** يؤثر على اللغة الحالية
- اللغة يتم تحديدها تلقائياً **فقط** في المرة الأولى

## خريطة البلدان واللغات

يدعم النظام حالياً أكثر من 50 دولة مع تحويلها إلى اللغات المناسبة:

### اللغة العربية (ar)
SA, EG, AE, MA, DZ, TN, JO, LB, SY, IQ, YE, KW, OM, QA, BH, PS

### اللغة الفرنسية (fr)
FR, BE, CH, CA

### اللغة الإنجليزية (en)
US, GB, AU, PH, MY, SG, وأي بلد غير مدعوم

### لغات أخرى
- DE, AT → de (ألماني)
- ES, MX, AR, CO, CL, PE → es (إسباني)
- IT → it (إيطالي)
- PT, BR → pt (برتغالي)
- وغيرها...

## ملاحظات مهمة

1. **حقل البلد إجباري** عند التسجيل وتحديث البروفايل
2. **اللغة التلقائية تطبق مرة واحدة فقط** عند أول زيارة
3. **المستخدم يمكنه تغيير اللغة يدوياً** في أي وقت
4. **تغيير البلد لا يؤثر على اللغة** بعد التحديد الأول
5. **اللغات المدعومة** تعتمد على ملف `config/languages.php`

## للتوسع والتخصيص

### إضافة مزيد من البلدان واللغات
عدل الملف `app/Services/LocaleService.php` وأضف على `COUNTRY_LOCALE_MAP`:

```php
private const COUNTRY_LOCALE_MAP = [
    'XX' => 'language_code', // رمز البلد => رمز اللغة
    // ...
];
```

### تخصيص سلوك تحديد اللغة
عدل الملف `app/Http/Middleware/AutoDetectUserLocale.php` حسب احتياجاتك.

## الاختبار

### اختبار التسجيل
1. اذهب إلى صفحة التسجيل
2. تأكد من ظهور حقل "البلد"
3. سجل حساب جديد
4. تحقق من حفظ `country_id` في قاعدة البيانات

### اختبار تحديد اللغة التلقائية
1. سجل مستخدم جديد واختر بلد عربي (مثل السعودية)
2. أكمل التسجيل
3. تحقق من تحول اللغة تلقائياً إلى العربية
4. غير اللغة يدوياً إلى الإنجليزية
5. سجل خروج ثم دخول مرة أخرى
6. تحقق من بقاء اللغة كما اخترتها (الإنجليزية) وعدم التغيير التلقائي

### اختبار تحديث البروفايل
1. اذهب إلى صفحة البروفايل
2. تأكد من ظهور حقل "البلد" مع القيمة الحالية
3. غير البلد وحفظ
4. تحقق من التحديث في قاعدة البيانات

## الأوامر المستخدمة

```bash
# تشغيل migrations
php artisan migrate

# إذا كنت تريد إعادة التشغيل
php artisan migrate:rollback
php artisan migrate
```
