<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialtyRequest;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialtiesController extends Controller
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
     * Display a listing of specialties.
     */
    public function index(Request $request)
    {
        $specialties = Specialty::with(['translations', 'topics'])
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);

        return view('dashboard.specialties.index', ['specialties' => $specialties]);
    }

    /**
     * Store a newly created specialty.
     */
    public function store(SpecialtyRequest $request)
    {
        try {
            DB::beginTransaction();

            $model = Specialty::create([
                'key' => $request->key,
                'icon' => $request->icon,
                'color' => $request->color,
                'sort_order' => $request->sort_order ?? 0,
                'active' => true,
            ]);

            // Save specialty translation
            $translationData = [
                'name' => $request->name,
                'description' => $request->description,
            ];
            $model->translateOrNew()->fill($translationData);
            $model->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('specialties.index'),
            'message' => __('translation.messages.added_successfully')
        ], 200);
    }

    /**
     * Update the specified specialty.
     */
    public function update(SpecialtyRequest $request, $lang, $id)
    {
        try {
            DB::beginTransaction();

            // Handle both model binding and direct ID
            $model = $id instanceof Specialty ? $id : Specialty::findOrFail($id);

            $model->update([
                'key' => $request->key,
                'icon' => $request->icon,
                'color' => $request->color,
                'sort_order' => $request->sort_order ?? $model->sort_order,
            ]);

            // Save specialty translation
            $translationData = [
                'name' => $request->name,
                'description' => $request->description,
            ];
            $model->translateOrNew()->fill($translationData);
            $model->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('specialties.index'),
            'message' => __('translation.messages.updated_successfully')
        ], 200);
    }

    /**
     * Remove the specified specialty.
     */
    public function destroy($lang, $id)
    {
       
        $model = $id instanceof Specialty ? $id : Specialty::findOrFail($id);

        // Topics will be deleted automatically due to cascade
        $model->delete();
        $model->deleteTranslations();

        return response()->json([
            'success' => true,
            'redirect' => route('specialties.index'),
            'message' => __('translation.messages.deleted_successfully')
        ], 200);
    }

    /**
     * Toggle the active status of a specialty.
     */
    public function updateActiveStatus($lang, $id = null)
    {
        $model = $id instanceof Specialty ? $id : Specialty::findOrFail($id);

        $active = $model->active ? 0 : 1;
        $model->update([
            'active' => $active
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('specialties.index'),
            'message' => __('translation.messages.activated_successfully')
        ], 200);
    }
}
