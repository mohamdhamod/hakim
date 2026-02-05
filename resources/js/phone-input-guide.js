// ========================================
// 📱 دليل المطور السريع - intl-tel-input
// Developer Quick Guide - intl-tel-input
// ========================================

// 1️⃣ الاستخدام البسيط (Simple Usage)
// ===================================
// فقط استخدم type="tel" في أي صفحة
// Just use type="tel" in any page

<input type="tel" id="phone" name="phone" class="form-control" required>

// ✅ تعمل تلقائياً! / Works automatically!


// 2️⃣ الوصول للمكتبة (Access Library)
// ====================================
const input = document.querySelector('#phone');
const iti = input.itiInstance;  // Instance object


// 3️⃣ الحصول على الرقم الكامل (Get Full Number)
// ===============================================
const fullNumber = iti.getNumber();
// Example: "+963123456789"


// 4️⃣ التحقق من صحة الرقم (Validate Number)
// ==========================================
if (iti.isValidNumber()) {
    console.log('✅ الرقم صحيح / Valid number');
    const number = iti.getNumber();
} else {
    console.log('❌ الرقم غير صحيح / Invalid number');
    const errorCode = iti.getValidationError();
}


// 5️⃣ معلومات الدولة (Country Information)
// =========================================
const countryData = iti.getSelectedCountryData();
// {
//     name: "Syria",
//     iso2: "sy",
//     dialCode: "963"
// }


// 6️⃣ تغيير الدولة برمجياً (Set Country Programmatically)
// =========================================================
iti.setCountry("sa");  // Saudi Arabia
iti.setCountry("ae");  // UAE


// 7️⃣ تعيين رقم (Set Number)
// ==========================
iti.setNumber("+963123456789");


// 8️⃣ إعادة تهيئة للمحتوى الديناميكي (Re-initialize for Dynamic Content)
// ========================================================================
// بعد إضافة محتوى جديد بـ AJAX
// After adding new content via AJAX
window.reinitPhoneInputs();


// 9️⃣ الاستماع للأحداث (Listen to Events)
// ========================================
input.addEventListener('countrychange', function() {
    const selectedCountry = iti.getSelectedCountryData();
    console.log('Selected country:', selectedCountry.name);
});

input.addEventListener('input', function() {
    console.log('Current value:', iti.getNumber());
});


// 🔟 التحقق اليدوي (Manual Validation)
// =====================================
form.addEventListener('submit', function(e) {
    if (!iti.isValidNumber()) {
        e.preventDefault();
        alert('رقم الهاتف غير صحيح / Invalid phone number');
        return false;
    }
    
    // تعيين الرقم الكامل قبل الإرسال
    // Set full number before submit
    input.value = iti.getNumber();
});


// ========================================
// 🔧 التخصيص (Customization)
// ========================================

// تغيير الدولة الافتراضية
// Change default country
// في phone-input.js:
initialCountry: "sy"  // رمز ISO / ISO code

// تغيير الدول المفضلة
// Change preferred countries
preferredCountries: ["gb", "fr", "de", "us", "sa"]

// السماح بأرقام غير صالحة
// Allow invalid numbers
strictMode: false

// إخفاء كود الدولة
// Hide dial code
separateDialCode: false

// الوضع الوطني (بدون كود دولي)
// National mode (without country code)
nationalMode: true


// ========================================
// 📊 أمثلة عملية (Practical Examples)
// ========================================

// مثال 1: التحقق قبل إرسال AJAX
// Example 1: Validate before AJAX submit
function submitPhoneForm() {
    const phoneInput = document.querySelector('#phone');
    const iti = phoneInput.itiInstance;
    
    if (!iti.isValidNumber()) {
        showError('رقم الهاتف غير صحيح');
        return;
    }
    
    const data = {
        phone: iti.getNumber(),
        country_code: iti.getSelectedCountryData().iso2
    };
    
    // Send AJAX...
}


// مثال 2: التحقق مع Bootstrap validation
// Example 2: Validate with Bootstrap validation
const phoneInput = document.querySelector('#phone');
const form = phoneInput.closest('form');

form.addEventListener('submit', function(e) {
    const iti = phoneInput.itiInstance;
    
    if (!iti.isValidNumber()) {
        e.preventDefault();
        e.stopPropagation();
        
        phoneInput.classList.add('is-invalid');
        
        // Show error message
        let feedback = phoneInput.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            phoneInput.parentElement.appendChild(feedback);
        }
        feedback.textContent = 'رقم الهاتف غير صالح';
        feedback.style.display = 'block';
        
        return false;
    }
    
    // Submit form...
});


// مثال 3: استخدام مع Modal
// Example 3: Use with Modal
const modal = document.querySelector('#myModal');
modal.addEventListener('shown.bs.modal', function() {
    // Re-initialize phone inputs in modal
    window.reinitPhoneInputs();
});


// مثال 4: تحديث الرقم من قاعدة البيانات
// Example 4: Update number from database
function loadUserPhone(phoneNumber) {
    const input = document.querySelector('#phone');
    const iti = input.itiInstance;
    
    if (iti && phoneNumber) {
        iti.setNumber(phoneNumber);
    }
}


// مثال 5: معالجة multiple phone inputs
// Example 5: Handle multiple phone inputs
document.querySelectorAll('input[type="tel"]').forEach(input => {
    const iti = input.itiInstance;
    
    input.addEventListener('blur', function() {
        if (iti.isValidNumber()) {
            // Mark as valid
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            // Mark as invalid
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    });
});


// ========================================
// 🎨 تخصيص CSS (CSS Customization)
// ========================================

/*
في phone-input.css يمكنك تخصيص:
In phone-input.css you can customize:

1. ألوان الحدود / Border colors
.iti__tel-input:focus {
    border-color: #YOUR_COLOR;
}

2. حجم الخط / Font size
.iti__tel-input {
    font-size: 1rem;
}

3. ألوان القائمة / Dropdown colors
.iti__country-list {
    background-color: #YOUR_COLOR;
}

4. حالة الخطأ / Error state
.iti__tel-input.is-invalid {
    border-color: #dc3545;
}
*/


// ========================================
// ⚠️ ملاحظات مهمة (Important Notes)
// ========================================

// 1. الرقم يُرسل بالصيغة الدولية الكاملة
//    Number is sent in full international format
//    Example: +963123456789

// 2. تأكد من التحقق في Laravel أيضاً
//    Make sure to validate in Laravel too
//    'phone' => 'required|regex:/^\+[1-9]\d{1,14}$/'

// 3. للمحتوى الديناميكي، استخدم reinitPhoneInputs()
//    For dynamic content, use reinitPhoneInputs()

// 4. يعمل تلقائياً مع:
//    Works automatically with:
//    - type="tel"
//    - id="phone"
//    - name="phone"


// ========================================
// 🐛 استكشاف الأخطاء (Troubleshooting)
// ========================================

// المشكلة: المكتبة لا تعمل
// Problem: Library not working
// الحل: تأكد من بناء الملفات
// Solution: Make sure to build files
// npm run build

// المشكلة: الدولة الافتراضية خاطئة
// Problem: Wrong default country
// الحل: تحقق من phone-input.js
// Solution: Check phone-input.js
// initialCountry: "sy"

// المشكلة: لا يعمل في Modal
// Problem: Not working in Modal
// الحل: استخدم reinitPhoneInputs()
// Solution: Use reinitPhoneInputs()
// modal.addEventListener('shown.bs.modal', () => {
//     window.reinitPhoneInputs();
// });


// ========================================
// 📚 روابط مفيدة (Useful Links)
// ========================================

// التوثيق الكامل:
// Full documentation:
// - PHONE_INPUT_IMPLEMENTATION.md
// - PHONE_INPUT_README.md

// الصفحة التجريبية:
// Demo page:
// - public/phone-input-demo.html

// الكود المصدري:
// Source code:
// - resources/js/phone-input.js
// - resources/css/phone-input.css

// المكتبة الأصلية:
// Original library:
// - https://github.com/jackocnr/intl-tel-input


// ========================================
// ✅ انتهى الدليل السريع
//    End of Quick Guide
// ========================================
