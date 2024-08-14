<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use DB;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view','roles');

        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('edit','roles');

        $role = Role::create($request->only('name'));

        if ($permission = $request->input('permissions')) {
            foreach ($permission as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('view','roles');

        return new RoleResource(Role::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('edit','roles');

        $role = Role::find($id);

        $role->update($request->only('name'));

        DB::table('role_permission')->where('role_id', $role->id)->delete();

        if ($permission = $request->input('permissions')) {
            foreach ($permission as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('edit','roles');

        DB::table('role_permission')->where('role_id', $id)->delete();

        Role::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
