<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\VaccinationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VaccinationTypesController extends Controller
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
     * Display a listing of vaccination types.
     */
    public function index(Request $request)
    {
        $items = VaccinationType::with(['translations'])
            ->orderBy('order')
            ->orderBy('age_group')
            ->paginate(12);

        return view('dashboard.vaccination_types.index', ['items' => $items]);
    }

    /**
     * Store a newly created vaccination type.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:100|unique:vaccination_types,key',
            'name' => 'required|string|max:255',
            'disease_prevented' => 'nullable|string|max:255',
            'recommended_age_months' => 'nullable|integer|min:0',
            'age_group' => 'nullable|string|max:50',
            'doses_required' => 'nullable|integer|min:1',
            'interval_days' => 'nullable|integer|min:0',
            'booster_after_months' => 'nullable|integer|min:0',
            'is_mandatory' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $model = VaccinationType::create([
                'key' => $request->key,
                'disease_prevented' => $request->disease_prevented,
                'recommended_age_months' => $request->recommended_age_months,
                'age_group' => $request->age_group,
                'doses_required' => $request->doses_required ?? 1,
                'interval_days' => $request->interval_days ?? 0,
                'booster_after_months' => $request->booster_after_months,
                'is_mandatory' => $request->boolean('is_mandatory'),
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
            'redirect' => route('vaccination_types.index'),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }

    /**
     * Update the specified vaccination type.
     */
    public function update(Request $request, $lang, $id)
    {
        $model = VaccinationType::findOrFail($id);

        $request->validate([
            'key' => 'required|string|max:100|unique:vaccination_types,key,' . $model->id,
            'name' => 'required|string|max:255',
            'disease_prevented' => 'nullable|string|max:255',
            'recommended_age_months' => 'nullable|integer|min:0',
            'age_group' => 'nullable|string|max:50',
            'doses_required' => 'nullable|integer|min:1',
            'interval_days' => 'nullable|integer|min:0',
            'booster_after_months' => 'nullable|integer|min:0',
            'is_mandatory' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $model->update([
                'key' => $request->key,
                'disease_prevented' => $request->disease_prevented,
                'recommended_age_months' => $request->recommended_age_months,
                'age_group' => $request->age_group,
                'doses_required' => $request->doses_required ?? $model->doses_required,
                'interval_days' => $request->interval_days ?? $model->interval_days,
                'booster_after_months' => $request->booster_after_months,
                'is_mandatory' => $request->boolean('is_mandatory'),
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
            'redirect' => route('vaccination_types.index'),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }

    /**
     * Remove the specified vaccination type.
     */
    public function destroy($lang, $id)
    {
        $model = VaccinationType::findOrFail($id);
        $model->delete();
        $model->deleteTranslations();

        return response()->json([
            'success' => true,
            'redirect' => route('vaccination_types.index'),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }

    /**
     * Toggle the active status.
     */
    public function updateActiveStatus($lang, $id = null)
    {
        $model = VaccinationType::findOrFail($id);

        $model->update([
            'is_active' => !$model->is_active
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('vaccination_types.index'),
            'message' => __('translation.messages.activated_successfully')
        ], 200);
    }
}
