<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CountriesRequest;
use App\Models\Country;
use App\Traits\FileHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::SETTING_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . PermissionEnum::SETTING_DELETE)->only('destroy');
        $this->middleware('permission:' . PermissionEnum::SETTING_VIEW)->only('index');
        $this->middleware('permission:' . PermissionEnum::SETTING_UPDATE)->only(['edit', 'update']);
    }
    use FileHandler;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        $countries = Country::with(['translations'])
            ->orderBy('id', 'asc')
            ->paginate(12);
        return view('dashboard.countries.index',['countries'=>$countries]);
    }


    public function store(CountriesRequest $request)
    {
        try {
            $flag = null;
            if ($request->hasFile('flag')) {
                $flag = $this->storeFile($request->file('flag'), 'countries', false);
            }
            $model = Country::create([
                'phone_extension'=>$request->phone_extension,
                'code'=>$request->code,
                'flag'=>$flag,
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
            'redirect' => route('countries.index'),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }
    public function update(CountriesRequest $request, $lang , $id)
    {
        $model = Country::findOrFail($id);
        $flag = $model->flag;
        if ($request->hasFile('flag')) {
            $flag = $this->updateFile($request->file('flag'),$model->flag,'countries',false);
        }
        $model->update([
            'phone_extension'=>$request->phone_extension,
            'code'=>$request->code,
            'flag'=>$flag,
        ]);
        $translationData = [
            'name' => $request->name,
        ];

        $model->translateOrNew()->fill($translationData);
        $model->save();
        return response()->json([
            'success' => true,
            'redirect' => route('countries.index'),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }
    public function destroy($lang , $id)
    {
        $model = Country::findOrFail($id);
        $model->delete();
        $model->deleteTranslations();
        return response()->json([
            'success' => true,
            'redirect' => route('countries.index'),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }
    public function updateActiveStatus(Request $request, $lang , $id)
    {

        $request->validate([
            'action' => 'required',
        ]);

        $model = Country::findOrFail($id);
        $model->update([
            'active' => $request->input('action') === 'activate' ? 1 : 0
        ]);
        return response()->json([
            'success' => true,
            'redirect' => route('countries.index'),
            'message' => __('translation.messages.activated_successfully')
        ], 200);
    }


}
