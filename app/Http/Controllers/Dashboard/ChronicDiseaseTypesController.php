<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\ChronicDiseaseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChronicDiseaseTypesController extends Controller
{
    /**
     * Constructor with permission middleware.
     */
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::MANAGE_SPECIALTIES_VIEW)->only(['index']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SPECIALTIES_ADD)->only(['store']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SPECIALTIES_UPDATE)->only(['update', 'updateActiveStatus']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SPECIALTIES_DELETE)->only(['destroy']);
    }

    /**
     * Display a listing of chronic disease types.
     */
    public function index(Request $request)
    {
        $items = ChronicDiseaseType::with(['translations'])
            ->orderBy('category')
            ->latest()
            ->paginate(12);

        return view('dashboard.chronic_disease_types.index', ['items' => $items]);
    }

    /**
     * Store a newly created chronic disease type.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:100|unique:chronic_disease_types,key',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'icd11_code' => 'nullable|string|max:50',
            'followup_interval_days' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $model = ChronicDiseaseType::create([
                'key' => $request->key,
                'category' => $request->category,
                'icd11_code' => $request->icd11_code,
                'followup_interval_days' => $request->followup_interval_days ?? 30,
                'is_active' => true,
            ]);

            // Save translation
            $model->translateOrNew(app()->getLocale())->fill([
                'name' => $request->name,
                'description' => $request->description,
                'management_guidelines' => $request->management_guidelines,
            ]);
            $model->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('chronic_disease_types.index'),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }

    /**
     * Update the specified chronic disease type.
     */
    public function update(Request $request, $lang, $id)
    {
        $model = ChronicDiseaseType::findOrFail($id);

        $request->validate([
            'key' => 'required|string|max:100|unique:chronic_disease_types,key,' . $model->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'icd11_code' => 'nullable|string|max:50',
            'followup_interval_days' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $model->update([
                'key' => $request->key,
                'category' => $request->category,
                'icd11_code' => $request->icd11_code,
                'followup_interval_days' => $request->followup_interval_days ?? $model->followup_interval_days,
            ]);

            // Save translation
            $model->translateOrNew(app()->getLocale())->fill([
                'name' => $request->name,
                'description' => $request->description,
                'management_guidelines' => $request->management_guidelines,
            ]);
            $model->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('chronic_disease_types.index'),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }

    /**
     * Remove the specified chronic disease type.
     */
    public function destroy($lang, $id)
    {
        $model = ChronicDiseaseType::findOrFail($id);
        $model->delete();
        $model->deleteTranslations();

        return response()->json([
            'success' => true,
            'redirect' => route('chronic_disease_types.index'),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }

    /**
     * Toggle the active status.
     */
    public function updateActiveStatus($lang, $id = null)
    {
        $model = ChronicDiseaseType::findOrFail($id);

        $model->update([
            'is_active' => !$model->is_active
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('chronic_disease_types.index'),
            'message' => __('translation.messages.activated_successfully')
        ], 200);
    }
}
