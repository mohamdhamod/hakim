<?php

namespace App\Http\Controllers;

use App\Enums\ConfigEnum;
use App\Models\ConfigTitle;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Display the privacy policy page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $content = ConfigTitle::with(['translations'])
            ->where('key', ConfigEnum::PRIVACY_POLICY)
            ->first();

        return view('pages.privacy-policy', [
            'content' => $content,
        ]);
    }
}
