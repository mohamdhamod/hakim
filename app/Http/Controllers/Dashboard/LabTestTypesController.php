<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\LabTestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabTestTypesController extends Controller
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
     * Display a listing of lab test types.
     */
    public function index(Request $request)
    {
        $items = LabTestType::with(['translations'])
            ->orderBy('order')
            ->orderBy('category')
            ->paginate(12);

        return view('dashboard.lab_test_types.index', ['items' => $items]);
    }

    /**
     * Store a newly created lab test type.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:100|unique:lab_test_types,key',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'normal_range_min' => 'nullable|numeric',
            'normal_range_max' => 'nullable|numeric',
            'normal_range_text' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $model = LabTestType::create([
                'key' => $request->key,
                'category' => $request->category,
                'unit' => $request->unit,
                'normal_range_min' => $request->normal_range_min,
                'normal_range_max' => $request->normal_range_max,
                'normal_range_text' => $request->normal_range_text,
                'order' => $request->order ?? 0,
                'is_active' => true,
            ]);

            // Save translation
            $model->translateOrNew(app()->getLocale())->fill([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            $model->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('lab_test_types.index'),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }

    /**
     * Update the specified lab test type.
     */
    public function update(Request $request, $lang, $id)
    {
        $model = LabTestType::findOrFail($id);

        $request->validate([
            'key' => 'required|string|max:100|unique:lab_test_types,key,' . $model->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'normal_range_min' => 'nullable|numeric',
            'normal_range_max' => 'nullable|numeric',
            'normal_range_text' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $model->update([
                'key' => $request->key,
                'category' => $request->category,
                'unit' => $request->unit,
                'normal_range_min' => $request->normal_range_min,
                'normal_range_max' => $request->normal_range_max,
                'normal_range_text' => $request->normal_range_text,
                'order' => $request->order ?? $model->order,
            ]);

            // Save translation
            $model->translateOrNew(app()->getLocale())->fill([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            $model->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('lab_test_types.index'),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }

    /**
     * Remove the specified lab test type.
     */
    public function destroy($lang, $id)
    {
        $model = LabTestType::findOrFail($id);
        $model->delete();
        $model->deleteTranslations();

        return response()->json([
            'success' => true,
            'redirect' => route('lab_test_types.index'),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }

    /**
     * Toggle the active status.
     */
    public function updateActiveStatus($lang, $id = null)
    {
        $model = LabTestType::findOrFail($id);

        $model->update([
            'is_active' => !$model->is_active
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('lab_test_types.index'),
            'message' => __('translation.messages.activated_successfully')
        ], 200);
    }
}
