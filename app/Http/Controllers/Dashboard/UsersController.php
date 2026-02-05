<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Enums\PermissionEnum;
use App\Traits\FileHandler;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    use PasswordValidationRules;
    use FileHandler;

    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::USERS_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . PermissionEnum::USERS_DELETE)->only('destroy');
        $this->middleware('permission:' . PermissionEnum::USERS_VIEW)->only('index');
        $this->middleware('permission:' . PermissionEnum::USERS_UPDATE)->only(['edit', 'update']);
    }

    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->roles, function ($query) use ($request) {
                return $query->whereHas('roles', function ($q) use ($request) {
                    $q->whereIn('name', (array) $request->roles);
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('dashboard.users.index', [
            'users' => $users,
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function edit($lang ,$id)
    {
        $model = User::findOrFail($id);
        return view('dashboard.users.edit', compact('model'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'phone'  => ['required', 'string', 'max:255'],
            'email'      => ['required','string','email','max:255', Rule::unique(User::class)],
            'password'   => $this->passwordRules(),
            'image'      => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
        ])->validate();

        $model = User::create([
            'name' => $input['name'],
            'phone'  => $input['phone'],
            'email'      => $input['email'],
            'password'   => $input['password'],            'country_id' => $input['country_id'],        ]);

        if ($request->hasFile('image')) {
            $storedPath = $this->storeFile($request->file('image'), 'users');
            $model->image = $storedPath;
            $model->save();
        }

        if ($request->filled('roles')) {
            $model->syncRoles($request->roles);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => __('translation.messages.added_successfully')], 200);
        }
        return redirect()->route('users.index')->with('status', __('translation.messages.added_successfully'));
    }

    public function update(Request $request, $lang, $id)
    {
        $model = User::findOrFail($id);
        $input = $request->all();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'phone'  => ['required', 'string', 'max:255'],
            'email'      => ['required','string','email','max:255'],
            'image'      => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
        ])->validate();

        $model->update([
            'name' => $input['name'],
            'phone'  => $input['phone'],
            'email'      => $input['email'],
            'country_id' => $input['country_id'],
        ]);

        if ($request->hasFile('image')) {
            $storedPath = $this->updateFile($request->file('image'), $model->image, 'users');
            $model->image = $storedPath;
            $model->save();
        }

        if ($request->filled('roles')) {
            $model->syncRoles($request->roles);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => __('translation.messages.updated_successfully')], 200);
        }
        return redirect()->route('users.index')->with('status', __('translation.messages.updated_successfully'));
    }

    public function destroy($lang, $id)
    {
        DB::beginTransaction();

        try {
            $model = User::findOrFail($id);
            
            // Delete user subscriptions
            \App\Models\UserSubscription::where('user_id', $model->id)->delete();
            
            // Delete user image if exists
            if ($model->image) {
                $this->deleteFile($model->image);
            }
            
            // Finally delete the user
            $model->delete();

            DB::commit();

            return redirect()->route('users.index')->with('status', __('translation.messages.successfully_deleted'));
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'error'   => $e->getMessage(),
                ], 500);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function change_password(Request $request,$lang, $id)
    {
        $model = User::findOrFail($id);
        $data = $request->all();
        Validator::make($data, [
            'password' => $this->passwordRules(),
        ])->validate();
        $model->forceFill([
            'password' => $data['password'],
        ])->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => __('translation.messages.password_updated_successfully')], 200);
        }
        return redirect()->route('users.index')->with('status', __('translation.messages.password_updated_successfully'));
    }


}
