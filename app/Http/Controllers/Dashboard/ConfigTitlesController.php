<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;

use App\Http\Requests\ConfigTitlesRequest;

use App\Http\Resources\ConfigTitleResource;

use App\Models\ConfigTitle;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;

class ConfigTitlesController extends Controller
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
     * @return Application|Factory|View|JsonResponse|AnonymousResourceCollection
     * @throws Exception
     */
    public function index(Request $request)
    {
        $pages = ConfigTitle::orderBy('page', 'ASC')->distinct()->pluck('page')->toArray();

        // Handle AJAX requests - Return JSON for DataTables
        if($request->ajax()){
            $query = ConfigTitle::with(['translations'])
                ->when($request->pages, function ($query) use ($request) {
                    return $query->whereIn('page', $request->pages);
                })
                ->when($request->get('search')['value'], function ($query) use ($request) {
                    $searchValue = $request->get('search')['value'];
                    return $query->where(function ($q) use ($searchValue) {
                        $q->where('key', 'like', '%' . $searchValue . '%')
                          ->orWhere('page', 'like', '%' . $searchValue . '%')
                          ->orWhereHas('translations', function ($translationQuery) use ($searchValue) {
                              $translationQuery->where('title', 'like', '%' . $searchValue . '%')
                                              ->orWhere('description', 'like', '%' . $searchValue . '%');
                          });
                    });
                });

            // Handle ordering
            if ($request->has('order')) {
                $orderColumn = $request->get('columns')[$request->get('order')[0]['column']]['data'];
                $orderDirection = $request->get('order')[0]['dir'];

                if (in_array($orderColumn, ['id', 'key', 'page', 'title'])) {
                    if ($orderColumn === 'title') {
                        $query->orderBy(function($q) {
                            $q->select('title')
                              ->from('config_title_translations')
                              ->whereColumn('config_title_translations.config_title_id', 'config_titles.id')
                              ->where('locale', app()->getLocale())
                              ->limit(1);
                        }, $orderDirection);
                    } else {
                        $query->orderBy($orderColumn, $orderDirection);
                    }
                }
            } else {
                $query->orderBy('page', 'ASC')->orderBy('id', 'ASC');
            }

            // Get pagination parameters
            $start = (int) $request->get('start', 0);
            $length = (int) $request->get('length', 25);

            // Get total count before pagination
            $totalRecords = ConfigTitle::count();
            $filteredRecords = $query->count();

            // Apply pagination
            $configTitles = $query->skip($start)->take($length)->get();

            // Transform data using Resource
            $data = ConfigTitleResource::collection($configTitles);

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data,
                'pages' => $pages,
                'total_pages' => count($pages)
            ]);
        }

        return view('dashboard.config.config_titles.index',['pages'=>$pages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ConfigTitlesRequest $request
     * @return JsonResponse
     */
    public function store(ConfigTitlesRequest $request)
    {
        $configTitle = new ConfigTitle();
        $configTitle->key = $request->key;
        $configTitle->page = $request->page;
        $configTitle->save();

        $translationData = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        $configTitle->translateOrNew()->fill($translationData);
        $configTitle->save();

        // Reload the model with translations
        $configTitle->load(['translations']);

        return response()->json([
            'success' => true,
            'message' => __('translation.messages.added_successfully'),
            'data' => new ConfigTitleResource($configTitle)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|ConfigTitleResource
     */
    public function show(Request $request, $lang , $id)
    {
        $configTitle = ConfigTitle::with(['translations'])->findOrFail($id);

        if($request->expectsJson() || $request->wantsJson()) {
            return new ConfigTitleResource($configTitle);
        }

        return response()->json([
            'success' => true,
            'data' => new ConfigTitleResource($configTitle)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ConfigTitlesRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ConfigTitlesRequest $request,$lang, $id)
    {
        $model = ConfigTitle::findOrFail($id);
        $translationData = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        $model->translateOrNew()->fill($translationData);
        $model->save();

        // Reload the model with translations
        $model->load(['translations']);

        return response()->json([
            'success' => true,
            'message' => __('translation.messages.updated_successfully'),
            'data' => new ConfigTitleResource($model)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($lang,$id)
    {
        $configTitle = ConfigTitle::findOrFail($id);
        $configTitle->delete();

        return response()->json([
            'success' => true,
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }
}
