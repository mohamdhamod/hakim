<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfigurationsRequest;
use App\Models\Configuration;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfigurationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::SETTING_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . PermissionEnum::SETTING_DELETE)->only('destroy');
        $this->middleware('permission:' . PermissionEnum::SETTING_VIEW)->only('index');
        $this->middleware('permission:' . PermissionEnum::SETTING_UPDATE)->only(['edit', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        $configurations = Configuration::with(['translations'])->latest()->paginate(12);
        return view('dashboard.configurations.index',['configurations'=>$configurations]);
    }


    public function store(ConfigurationsRequest $request)
    {
        try {
            $model = Configuration::create([
                'key'=>$request->key,
                'score'=>$request->score ?? 0,
            ]);
            $translationData = [
                'name' => $request->name,
            ];
            $model->translateOrNew()->fill($translationData);
            $model->save();

        } catch (\Exception $e) {
            return response()->json(['success' => true,'message'=>$e->getMessage()],200);
        }
        return response()->json([
            'success' => true,
            'redirect' => route('configurations.index'),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }
    public function update(ConfigurationsRequest $request, $lang , $id)
    {
        $model = Configuration::findOrFail($id);
        $model->update([
           'key'=>$request->key,
            'score'=>$request->score ?? 0,
        ]);
        $translationData = [
            'name' => $request->name,
        ];

        $model->translateOrNew()->fill($translationData);
        $model->save();
        return response()->json([
            'success' => true,
            'redirect' => route('configurations.index'),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }
    public function destroy($lang , $id)
    {
        $model = Configuration::findOrFail($id);
        $model->delete();
        $model->deleteTranslations();
        return response()->json([
            'success' => true,
            'redirect' => route('configurations.index'),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }
    public function updateActiveStatus($lang, $id)
    {
        $model = Configuration::findOrFail($id);
        $active = $model->active ? 0 : 1;
        $model->update([
            'active' => $active
        ]);
        return response()->json([
            'success' => true,
            'redirect' => route('configurations.index'),
            'message' => __('translation.messages.activated_successfully')
        ], 200);
    }


}
