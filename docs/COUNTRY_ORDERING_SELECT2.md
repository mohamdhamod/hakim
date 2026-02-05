# تحديث: ترتيب البلدان و Select2 مع الأعلام

## التغييرات المطبقة

### ✅ 1. ترتيب البلدان في القائمة
تم إضافة scope في `Country.php` model لترتيب البلدان بحيث تظهر البلدان الرئيسية أولاً:

**الترتيب:**
1. 🇺🇸 USA (الولايات المتحدة)
2. 🇬🇧 UK (بريطانيا) 
3. 🇩🇪 Germany (ألمانيا)
4. 🇫🇷 France (فرنسا)
5. 🇪🇸 Spain (إسبانيا)
6. 🇸🇦 Saudi Arabia (السعودية)
7. باقي البلدان (مرتبة حسب ID)

### ✅ 2. استخدام Select2 مع أعلام الدول
تم تطبيق Select2 على جميع قوائم البلدان مع عرض علم كل دولة بجانب اسمها للحصول على:
- ✅ بحث سريع في القائمة
- ✅ واجهة احترافية وأنيقة
- ✅ عرض أعلام الدول 🏴
- ✅ دعم Placeholder
- ✅ Responsive Design

### الملفات المعدلة

#### 1. `app/Models/Country.php`
- إضافة `scopeOrderedWithPriority()` method

#### 2. Views - قوائم البلدان
تم تحديث 4 ملفات:
- ✅ `resources/views/auth/register-complete.blade.php`
- ✅ `resources/views/auth/profile.blade.php`
- ✅ `resources/views/dashboard/users/create.blade.php`
- ✅ `resources/views/dashboard/users/edit.blade.php`

**التغييرات في كل ملف:**
1. إضافة class `select2` إلى `<select>`
2. استبدال `->get()` بـ `->orderedWithPriority()->get()`
3. إضافة `data-flag` attribute لكل option
4. إضافة كود JavaScript لتهيئة Select2 مع عرض الأعلام

### الكود المستخدم

#### في Model:
```php
public function scopeOrderedWithPriority($query)
{
    return $query->orderByRaw("
        CASE 
            WHEN code = 'US' THEN 1
            WHEN code = 'GB' THEN 2
            WHEN code = 'DE' THEN 3
            WHEN code = 'FR' THEN 4
            WHEN code = 'ES' THEN 5
            WHEN code = 'SA' THEN 6
            ELSE 7
        END
    ")->orderBy('id');
}
```

#### في Views:
```php
// Before
@foreach(\App\Models\Country::where('is_active', 1)->get() as $country)

// After - مع الترتيب والأعلام
@foreach(\App\Models\Country::where('is_active', 1)->orderedWithPriority()->get() as $country)
    <option value="{{ $country->id }}" data-flag="{{ $country->flag_url }}">
```

```html
<!-- Before -->
<select class="form-select" ...>

<!-- After -->
<select class="form-select select2" ...>
```

#### JavaScript مع عرض الأعلام:
```javascript
// Initialize Select2 with flag template
$('#country_id').select2({
    placeholder: '{{ __('translation.auth.select_country') }}',
    allowClear: false,
    width: '100%',
    templateResult: formatCountryOption,
    templateSelection: formatCountryOption
});

// Format country option with flag
function formatCountryOption(country) {
    if (!country.id) {
        return country.text;
    }
    var $country = $(
        '<span><img src="' + $(country.element).data('flag') + '" class="img-flag" style="width: 20px; height: 15px; margin-right: 8px; object-fit: cover;" /> ' + country.text + '</span>'
    );
    return $country;
}
```

## الاختبار

### ✅ للتحقق من الترتيب:
1. افتح أي صفحة تحتوي على قائمة البلدان
2. افتح القائمة المنسدلة
3. تحقق من ظهور البلدان بالترتيب:
   - USA
   - UK  
   - Germany
   - France
   - Spain
   - Saudi Arabia
   - باقي البلدان

### ✅ للتحقق من Select2 والأعلام:
1. افتح القائمة المنسدلة
2. **تحقق من ظهور أعلام الدول** بجانب أسماء البلدان 🏴
3. **ابحث عن بلد معين** (مثلاً: اكتب "Egypt")
4. تحقق من ظهور النتائج المطابقة فقط مع الأعلام
5. تحقق من التصميم الاحترافي للقائمة

## الصفحات المتأثرة

### للمستخدمين:
- ✅ صفحة التسجيل (Register)
- ✅ صفحة البروفايل (Profile)

### للإدارة (Dashboard):
- ✅ صفحة إضافة مستخدم جديد
- ✅ صفحة تعديل بيانات المستخدم

## متطلبات التشغيل

تأكد من أن Select2 محملة في التطبيق:
- ✅ Select2 CSS موجودة في الـ layout
- ✅ Select2 JavaScript موجودة في الـ layout
- ✅ jQuery محملة (Select2 تعتمد عليها)

**ملاحظة:** الأعلام تستخدم `flag_url` attribute من Country Model الذي يعيد:
- مسار العلم من `storage/flags/` إذا كان محفوظاً
- أو مسار افتراضي من `images/flags/1x1/{code}.svg`

## ملاحأعلام تظهر تلقائياً** باستخدام `data-flag` attribute و `flag_url` من Country Model
4. **الترتيب لا يؤثر** على باقي استعلامات Country في التطبيق إلا عند استخدام scope
5. **يمكن إضافة المزيد من البلدان** للترتيب المخصص بتعديل `scopeOrderedWithPriority()`
6. **الأعلام تعمل مع أي حجم شاشة** - responsive design
1. **الترتيب يطبق تلقائياً** عند استخدام `->orderedWithPriority()`
2. **Select2 يعمل فقط إذا كانت المكتبة محملة** في الصفحة
3. **الترتيب لا يؤثر** على باقي استعلامات Country في التطبيق إلا عند استخدام scope
4. **يمكن إضافة المزيد من البلدان** للترتيب المخصص بتعديل `scopeOrderedWithPriority()`

## للتخصيص

### تغيير ترتيب البلدان:
عدل الـ scope في `app/Models/Country.php`:
```php
CASE 
    WHEN code = 'NEW_CODE' THEN 1  // أضف هنا
    WHEN code = 'US' THEN 2        // غير الأرقام
    // ...
END
``` والأعلام:
عدل الـ JavaScript في الـ views:
```javascript
$('#country_id').select2({
    placeholder: 'اختر البلد',
    allowClear: true,           // للسماح بالإلغاء
    width: '100%',
    minimumInputLength: 2,      // عدد أحرف البحث الأدنى
    templateResult: formatCountryOption,
    templateSelection: formatCountryOption
});

// تخصيص حجم العلم
function formatCountryOption(country) {
    if (!country.id) return country.text;
    return $('<span><img src="' + $(country.element).data('flag') + '" style="width: 25px; height: 18px; margin-right: 10px;" /> ' + country.text + '</span>');
} minimumInputLength: 2,      // عدد أحرف البحث
✅ الأعلام تظهر بشكل احترافي في القوائم! 🏴 الأدنى
    // ...
});
```

---

✅ جميع التعديلات تمت بنجاح!
✅ لا توجد أخطاء برمجية!
✅ تم تطبيق التغييرات على جميع الصفحات المطلوبة!
