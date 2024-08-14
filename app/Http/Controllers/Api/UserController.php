<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\updateInfoRequest;
use App\Http\Requests\updatePasswordRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'password' => bcrypt($request->get('password')),
        ]);

        return response(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return  User::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $user->update($request->only('first_name','last_name','email'));

        return Response(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $user->destroy();

        return response(Response::HTTP_OK);
    }

    /**
     * Get the user authentication i
     */
    public function user(){

        return Auth::user();
    }

    /**
     * update the user authentication information
     */
    public function updateInfo(updateInfoRequest $request)
    {
        $user = Auth::user();

        $user->update($request->only('first_name','last_name','email'));

        return Response($user,Response::HTTP_ACCEPTED);
    }
    /**
     * update the user authentication information
     */
    public function updatePassword( updatePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return Response($user,Response::HTTP_ACCEPTED);
    }


}
