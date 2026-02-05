# Quick Start: Country & Auto-Locale Feature

## What was added?

✅ **Country field** added to users table (required)
✅ **Auto-detect locale** on first login based on user's country
✅ **User can manually change language** anytime
✅ **Language only auto-set ONCE** per user

## Files Created

1. `database/migrations/2026_02_02_000001_add_country_id_to_users_table.php`
2. `app/Services/LocaleService.php`
3. `app/Http/Middleware/AutoDetectUserLocale.php`
4. `docs/COUNTRY_AND_LOCALE_FEATURE.md`
5. `docs/COUNTRY_LOCALE_DEVELOPER_GUIDE.md`

## Files Modified

1. `app/Models/User.php` - Added `locale_auto_detected` cast
2. `app/Actions/Fortify/CreateNewUser.php` - Added country validation & save
3. `app/Actions/Fortify/UpdateUserProfileInformation.php` - Added country update
4. `app/Http/Controllers/Dashboard/UsersController.php` - Added country management
5. `resources/views/auth/register-complete.blade.php` - Added country field
6. `resources/views/auth/profile.blade.php` - Added country field
7. `resources/views/dashboard/users/edit.blade.php` - Added country field
8. `bootstrap/app.php` - Registered new middleware
9. Translation files (ar, en, fr) - Added country translations

## How it works?

### On Registration
- User selects country (required) ✅
- `country_id` saved, `locale_auto_detected = false`

### On First Login
- Middleware detects: user logged in + `locale_auto_detected = false` + has country
- Sets locale automatically based on country code (e.g., SA → ar, FR → fr)
- Updates `locale_auto_detected = true`

### Subsequent Logins
- Language NOT changed automatically ✅
- User's manual language choice persists
- User can change language via language switcher

## Country → Locale Mapping

```
Arabic (ar):  SA, EG, AE, MA, DZ, TN, JO, LB, SY, IQ, YE, KW, OM, QA, BH, PS
French (fr):  FR, BE, CH, CA
German (de):  DE, AT
Spanish (es): ES, MX, AR, CO, CL, PE
English (en): US, GB, AU, PH, MY, SG, and default for unsupported
... and more (50+ countries supported)
```

## Testing

```bash
# Run migration
php artisan migrate

# Test:
1. Register new user → select country → check auto-locale on first login
2. Change language manually → logout/login → check language persists
3. Update profile → change country → check language doesn't change
```

## Read More

- `docs/COUNTRY_AND_LOCALE_FEATURE.md` - Full feature documentation (Arabic)
- `docs/COUNTRY_LOCALE_DEVELOPER_GUIDE.md` - Developer guide (Arabic)
