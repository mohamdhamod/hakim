<?php

namespace App\Http\Controllers;

use App\Enums\BannerTypeEnum;
use App\Enums\ConfigEnum;
use App\Enums\RoleEnum;
use App\Models\ConfigImage;
use App\Models\ConfigLink;
use App\Models\ConfigTitle;
use App\Models\Specialty;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Show the application backend.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $keys = [
            ConfigEnum::ABOUT_US_HERO,
            ConfigEnum::ABOUT_US_ABOUT_TITLE,
            ConfigEnum::ABOUT_US_ABOUT_BODY_1,
            ConfigEnum::ABOUT_US_ABOUT_BODY_2,
            ConfigEnum::ABOUT_US_HIGHLIGHT,
            ConfigEnum::ABOUT_US_OFFER_TITLE,
            ConfigEnum::ABOUT_US_VISION,
            ConfigEnum::ABOUT_US_MISSION,
            ConfigEnum::ABOUT_US_WHY_TITLE,
            ConfigEnum::ABOUT_US_WHY_SIMPLE_UI,
            ConfigEnum::ABOUT_US_WHY_SMART_TOOLS,
            ConfigEnum::ABOUT_US_WHY_PRO_WITHOUT_COMPLEXITY,
            ConfigEnum::ABOUT_US_WHY_FOR_INDIVIDUALS_COMPANIES,
            ConfigEnum::ABOUT_US_WHY_CONTINUOUS_SUPPORT,
            ConfigEnum::ABOUT_US_CTA,
            ConfigEnum::ABOUT_US_CTA_BUTTON,

        ];

        $about = ConfigTitle::with(['translations'])
            ->whereIn('key', $keys)
            ->get()
            ->keyBy('key');

        $specialties = Specialty::active()->ordered()->get();

        return view('about_us.index', [
            'about' => $about,
            'specialties' => $specialties,
        ]);
    }



}
