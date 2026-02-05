<?php

namespace App\Providers;

use App\Enums\ConfigEnum;


use App\Enums\ConfigurationsTypeEnum;
use App\Models\ConfigImage;
use App\Models\ConfigLink;
use App\Models\ConfigTitle;
use App\Models\Configuration;
use App\Models\Country;

use App\Models\TopCourse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Keep this method clean for binding services only
    }

    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

    Paginator::useBootstrap();
    // Use custom pagination views globally
    Paginator::defaultView('vendor.pagination.custom');
    Paginator::defaultSimpleView('vendor.pagination.custom-simple');

        if (Schema::hasTable('config_images')) {

            $config_images = ConfigImage::whereIn('key', [
                ConfigEnum::LOGO,
                ConfigEnum::DARK_LOGO,
                ConfigEnum::FAVICON,
            ])->get()->keyBy('key');
        }
        if (Schema::hasTable('config_links')) {
            $links = ConfigLink::whereIn('key', [
                ConfigEnum::FOOTER_LINKED_IN,
                ConfigEnum::FOOTER_FACEBOOK,
                ConfigEnum::FOOTER_TWITTER,
                ConfigEnum::FOOTER_INSTAGRAM,
                ConfigEnum::INFO_EMAIL,
                ConfigEnum::INFO_PHONE,
                ConfigEnum::DOMAIN,
            ])->get()->keyBy('key');
        }

        if (Schema::hasTable('config_titles')) {
            $termsTitle = ConfigTitle::where('key', ConfigEnum::TERMS_CONDITIONS_AND_AGREEMENTS)->first();
            $COPYRIGHT = ConfigTitle::where('key', ConfigEnum::COPYRIGHT)->first();
        }
        if (Schema::hasTable('configurations')) {

            $configurations = Configuration::whereIn('key', [
                ConfigurationsTypeEnum::CURRENCIES,
            ])->get();
        }

        $datatableLang = [
            'ar' => asset('backend/vendor/simple-datatables/ar.json'),
            'es' => asset('backend/vendor/simple-datatables/es.json'),
            'fr' => asset('backend/vendor/simple-datatables/fr.json'),
        ][app()->getLocale()] ?? '';


        View::share([
            'config_images'           => $config_images ?? [],
            'configurations'   => $configurations ?? [],
            'links'            => $links ?? [],
            'TERMS_CONDITIONS_AND_AGREEMENTS' => $termsTitle ?? null,
            'language' => $datatableLang ?? null,
            'COPYRIGHT'=>$COPYRIGHT ?? null,
        ]);
    }
}


