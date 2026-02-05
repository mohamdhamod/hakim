<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ConfigEmailLinksRequest;
use App\Http\Requests\ConfigImageRequest;
use App\Http\Requests\FAQRequest;
use App\Http\Requests\ValueRequest;
use App\Models\Category;
use App\Models\ConfigEmailLink;
use App\Models\ConfigImage;
use App\Models\ConfigLink;
use App\Models\Contact;
use App\Models\FAQ;
use App\Models\User;
use App\Models\Value;
use App\Traits\FileHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ConfigEmailsLinksController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::SETTING_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . PermissionEnum::SETTING_DELETE)->only('destroy');
        $this->middleware('permission:' . PermissionEnum::SETTING_VIEW)->only('index');
        $this->middleware('permission:' . PermissionEnum::SETTING_UPDATE)->only(['edit', 'update']);
    }
    use FileHandler;

    public function index(Request $request)
    {

        $links = ConfigLink::latest()->paginate(12);
        return view('dashboard.config.email_links.index', compact('links'));
    }

    public function update(ConfigEmailLinksRequest $request,$lang, $id)
    {

        $model = ConfigLink::findOrFail($id);
        $model->update([
            'name' => $request->name,
        ]);
return response()->json([
            'success' => true,
            'redirect' => route('config_email_links.index'),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    
    }
}
