<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $items = User::with(['country','country.translations'])
            ->select('id','name', 'email', 'country_id','phone')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', RoleEnum::ADMIN);
            });
        return  DataTables::eloquent($items)
            ->addColumn('action', function ($item) {
                return '<a class="view btn btn-sm btn-success" style="color:#fff" ><i class="bi bi-eye-fill"></i></a>';
            })
            ->addColumn('name', function($item){
                return  $item->name;
            })
            ->addColumn('roleNames', function($row){
                return  implode('-', $row->getRoleNames()->toArray()) ?? '';
            })
            ->rawColumns(['action' , 'roleNames','name'])
            ->make(true);
    }
}
