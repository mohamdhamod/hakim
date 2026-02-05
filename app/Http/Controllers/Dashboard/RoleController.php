<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\CompanySubscription;
use App\Traits\FileHandler;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_ROLES);
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
        $permissions = Permission::with(['roles'])
            ->when($request->pages, function ($query) use ($request) {
                return $query->whereIn('page', $request->pages);
            })->when($request->roles, function ($query) use ($request) {
                return $query->whereHas('roles',function ($query) use ($request) {
                    $query->whereIn('name', $request->roles);
                });
            })->latest()
            ->paginate(10);


        $pages = Permission::select('page')->whereNotNull('page')->distinct()->pluck('page');
        return view('dashboard.roles.index',['roles'=>Role::all(),'pages'=>$pages , 'permissions'=>$permissions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request,$lang, $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            if (!empty($request->roles)) {
                // Retrieve the roles based on the IDs sent in the request
                $roles = Role::whereIn('name', $request->roles)->get();

                // Sync the permissions for each role
                foreach ($roles as $role) {
                    $role->givePermissionTo($permission);
                }

                // Detach roles that are associated with the permission but not in the request
                $currentRoles = $permission->roles()->pluck('name')->toArray();
                $rolesToRemove = array_diff($currentRoles, $request->roles);
                foreach ($rolesToRemove as $roleName) {
                    $role = Role::where('name', $roleName)->first();
                    if ($role) {
                        $role->revokePermissionTo($permission);
                    }
                }
            } else {
                // If no roles are selected, detach all roles
                $permission->roles()->detach();
            }
                        return response()->json([
                            'success' => true,
                            'redirect' => route('roles.index'),
                            'message' => __('translation.messages.updated_successfully')
                        ], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
