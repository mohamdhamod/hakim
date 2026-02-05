<?php

namespace App\Http\Middleware;

use App\Services\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AutoDetectUserLocale
{
    /**
     * Handle an incoming request.
     * 
     * يتم تطبيق هذا الـ middleware بعد LocaleFromUrl
     * للتحقق من اللغة التلقائية للمستخدمين الجدد
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // إذا كان المستخدم مسجل دخول ولم يتم تحديد اللغة تلقائياً من قبل
        if (LocaleService::shouldAutoDetectLocale($user)) {
            // الحصول على اللغة بناءً على بلد المستخدم
            $detectedLocale = LocaleService::getLocaleForUser($user);
            
            // تطبيق اللغة
            LocaleService::setLocale($detectedLocale);
            Session::put('applocale', $detectedLocale);
            
            // تحديث حالة المستخدم لتفعيل أن اللغة تم اكتشافها
            $user->locale_auto_detected = true;
            $user->save();
        }

        return $next($request);
    }
}
