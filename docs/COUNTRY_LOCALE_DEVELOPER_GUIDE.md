# دليل المطور: ميزة البلد واللغة التلقائية

## نظرة عامة
تم تطوير نظام متكامل لإدارة بلد المستخدم وتحديد اللغة تلقائياً عند أول استخدام للمنصة.

## المكونات الرئيسية

### 1. LocaleService - خدمة تحديد اللغة
```php
// تحديد اللغة بناءً على رمز البلد
LocaleService::getLocaleByCountryCode('SA'); // يعود 'ar'
LocaleService::getLocaleByCountryCode('FR'); // يعود 'fr'

// تحديد اللغة بناءً على المستخدم
$locale = LocaleService::getLocaleForUser($user);

// التحقق من الحاجة لتحديد اللغة تلقائياً
if (LocaleService::shouldAutoDetectLocale($user)) {
    // تطبيق اللغة التلقائية
}
```

### 2. AutoDetectUserLocale Middleware
هذا الـ middleware يعمل بشكل تلقائي في كل request:

```php
// في bootstrap/app.php
$middleware->group('web', [
    // ... middlewares أخرى
    \App\Http\Middleware\AutoDetectUserLocale::class,
]);
```

**سلوك العمل:**
1. يتحقق إذا كان المستخدم مسجل دخول
2. يتحقق إذا كانت `locale_auto_detected = false`
3. يحدد اللغة بناءً على البلد
4. يطبق اللغة على Session
5. يحدث `locale_auto_detected` إلى `true`

### 3. خريطة البلدان واللغات

```php
// في LocaleService::COUNTRY_LOCALE_MAP
[
    // دول عربية
    'SA' => 'ar', // السعودية
    'EG' => 'ar', // مصر
    'AE' => 'ar', // الإمارات
    // ...
    
    // دول فرنسية
    'FR' => 'fr', // فرنسا
    'BE' => 'fr', // بلجيكا (فرنسي)
    // ...
    
    // دول إنجليزية
    'US' => 'en', // أمريكا
    'GB' => 'en', // بريطانيا
    // ...
]
```

## حقول قاعدة البيانات

### جدول users
```sql
-- حقل البلد (foreign key)
country_id BIGINT UNSIGNED NULL
FOREIGN KEY (country_id) REFERENCES countries(id)

-- حقل تتبع تحديد اللغة
locale_auto_detected BOOLEAN DEFAULT FALSE
```

### استعلامات مفيدة
```sql
-- الحصول على المستخدمين الذين لم يتم تحديد لغتهم
SELECT * FROM users WHERE locale_auto_detected = FALSE;

-- الحصول على توزيع المستخدمين حسب البلد
SELECT c.name, COUNT(*) as count 
FROM users u 
JOIN countries c ON u.country_id = c.id 
GROUP BY c.id;
```

## سيناريوهات الاستخدام

### السيناريو 1: مستخدم جديد من السعودية
1. المستخدم يسجل ويختار "السعودية" كبلد ✅
2. `country_id = [ID السعودية]`, `locale_auto_detected = false`
3. عند أول تسجيل دخول، AutoDetectUserLocale يحدد:
   - البلد = السعودية (SA)
   - اللغة المقترحة = ar (عربي)
4. اللغة تتحول إلى العربية تلقائياً ✅
5. `locale_auto_detected = true`
6. في الزيارات التالية، لن يتم تغيير اللغة تلقائياً ✅

### السيناريو 2: مستخدم يغير اللغة يدوياً
1. المستخدم لغته تحولت إلى العربية تلقائياً
2. يختار اللغة الإنجليزية من قائمة اللغات
3. اللغة تتحول إلى الإنجليزية ✅
4. `Session::put('applocale', 'en')`
5. في الزيارات التالية، تبقى اللغة إنجليزية ✅
6. لن يتم إعادة التحديد التلقائي لأن `locale_auto_detected = true` ✅

### السيناريو 3: مستخدم يغير البلد في البروفايل
1. المستخدم من السعودية، لغته عربي
2. يذهب للبروفايل ويغير البلد إلى فرنسا
3. البلد يتحدث في قاعدة البيانات ✅
4. **اللغة لا تتغير** (تبقى عربي) ✅
5. لأن `locale_auto_detected = true`

### السيناريو 4: مستخدم بدون بلد محدد
1. مستخدم قديم في قاعدة البيانات `country_id = NULL`
2. الـ middleware لا يعمل (شرط `shouldAutoDetectLocale` = false)
3. اللغة تبقى حسب Session أو fallback_locale
4. عند تحديث البروفايل، يجب اختيار بلد ✅

## تخصيصات إضافية

### إضافة لغة جديدة لبلد
```php
// في LocaleService.php
private const COUNTRY_LOCALE_MAP = [
    'XX' => 'new_lang', // أضف هنا
];
```

### تغيير سلوك التحديد التلقائي
```php
// في AutoDetectUserLocale.php
public function handle(Request $request, Closure $next)
{
    $user = Auth::user();
    
    // أضف شروط إضافية هنا
    if (LocaleService::shouldAutoDetectLocale($user) && YOUR_CONDITION) {
        // ...
    }
}
```

### إعادة تفعيل التحديد التلقائي لمستخدم معين
```sql
-- في قاعدة البيانات
UPDATE users SET locale_auto_detected = FALSE WHERE id = [USER_ID];
```

## نقاط الاتصال في الكود

### عند التسجيل
```php
// app/Actions/Fortify/CreateNewUser.php
User::create([
    'country_id' => $input['country_id'], // ✅
    'locale_auto_detected' => false, // ✅
]);
```

### عند تحديث البروفايل
```php
// app/Actions/Fortify/UpdateUserProfileInformation.php
$user->forceFill([
    'country_id' => $input['country_id'], // ✅
    // locale_auto_detected لا يتغير ✅
]);
```

### عند تشغيل Middleware
```php
// app/Http/Middleware/AutoDetectUserLocale.php
if (LocaleService::shouldAutoDetectLocale($user)) {
    $locale = LocaleService::getLocaleForUser($user);
    LocaleService::setLocale($locale);
    Session::put('applocale', $locale);
    $user->locale_auto_detected = true;
    $user->save();
}
```

## الاختبارات الموصى بها

### 1. اختبار التسجيل
```php
// Test: المستخدم يجب أن يختار بلد
$response = $this->post(route('register'), [
    'name' => 'Test User',
    'email' => 'test@gmail.com',
    // country_id مفقود
]);
$response->assertSessionHasErrors('country_id');
```

### 2. اختبار التحديد التلقائي
```php
// Test: اللغة تتحول تلقائياً عند أول زيارة
$user = User::factory()->create([
    'country_id' => $saudiArabiaId,
    'locale_auto_detected' => false,
]);
$this->actingAs($user)->get(route('home'));
$this->assertEquals('ar', App::getLocale());
$this->assertTrue($user->fresh()->locale_auto_detected);
```

### 3. اختبار عدم التغيير في الزيارات التالية
```php
// Test: اللغة لا تتغير في الزيارات التالية
$user = User::factory()->create([
    'country_id' => $saudiArabiaId,
    'locale_auto_detected' => true,
]);
Session::put('applocale', 'en');
$this->actingAs($user)->get(route('home'));
$this->assertEquals('en', App::getLocale());
```

## الأمان والأداء

### الأمان
- ✅ validation على country_id موجود في كل الفورمات
- ✅ foreign key constraint في قاعدة البيانات
- ✅ التحقق من اللغات المدعومة قبل التطبيق

### الأداء
- ✅ الـ middleware يعمل مرة واحدة فقط لكل مستخدم
- ✅ استعلام بسيط في قاعدة البيانات
- ✅ لا توجد استعلامات إضافية بعد التحديد الأول
- ⚠️ إذا كان عدد المستخدمين كبير جداً، فكر في caching country locale map

## الخلاصة

✅ حقل البلد إجباري في التسجيل والبروفايل
✅ اللغة تتحدد تلقائياً عند أول زيارة فقط
✅ المستخدم يمكنه تغيير اللغة يدوياً في أي وقت
✅ تغيير البلد لا يؤثر على اللغة بعد التحديد الأول
✅ يدعم أكثر من 50 دولة مع تحويلها إلى اللغات المناسبة
✅ قابل للتوسع والتخصيص بسهولة
