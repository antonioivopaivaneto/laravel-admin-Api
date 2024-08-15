<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\updateInfoRequest;
use App\Http\Requests\updatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     title="API React",
 *     version="1.0.0",
 *     description="Descrição da API"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticação por JWT"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     security={{"bearerAuth":{}}},
     *     summary="Get Users",
     *     description="Retrieve a paginated list of users.",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Número da página para paginação",
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários retornada com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        Gate::authorize('view', 'users');

        $user = User::paginate(10);

        return UserResource::collection($user);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     security={{"bearerAuth":{}}},
     *     summary="Create a new user",
     *     description="Create a new user with specified details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação dos dados enviados."
     *     )
     * )
     */
    public function store(UserCreateRequest $request)
    {
        Gate::authorize('edit', 'users');

        $user = User::create([
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'role_id' => $request->get('role_id'),
            'password' => bcrypt($request->get('password')),
        ]);

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     security={{"bearerAuth":{}}},
     *     summary="Show User",
     *     description="Retrieve a single user by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário retornado com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado."
     *     )
     * )
     */
    public function show(string $id)
    {
        Gate::authorize('view', 'users');

        $user = User::find($id);

        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     security={{"bearerAuth":{}}},
     *     summary="Update User",
     *     description="Update user details by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="first_name",
     *                 type="string",
     *                 description="First name of the user"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 type="string",
     *                 description="Last name of the user"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="Email address of the user"
     *             ),
     *             @OA\Property(
     *                 property="role_id",
     *                 type="integer",
     *                 description="ID of the user's role"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação dos dados enviados."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado."
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('edit', 'users');

        $user = User::find($id);

        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return response(new UserResource($user), Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     security={{"bearerAuth":{}}},
     *     summary="Delete User",
     *     description="Delete a user by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário deletado com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado."
     *     )
     * )
     */
    public function destroy(string $id)
    {
        Gate::authorize('edit', 'users');

        $user = User::find($id);

        $user->delete(); // Use delete() instead of destroy()

        return response()->json(['message' => 'User deleted successfully.'], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     security={{"bearerAuth":{}}},
     *     summary="Get authenticated user",
     *     description="Retrieve the currently authenticated user.",
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado retornado com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     */
    public function user()
    {
        $user = Auth::user();

        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions(),
            ]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/user/info",
     *     security={{"bearerAuth":{}}},
     *     summary="Update user information",
     *     description="Update the authenticated user's information.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="first_name",
     *                 type="string",
     *                 description="First name of the user"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 type="string",
     *                 description="Last name of the user"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="Email address of the user"
     *             ),
     *             @OA\Property(
     *                 property="role_id",
     *                 type="integer",
     *                 description="ID of the user's role"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Informações do usuário atualizadas com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação dos dados enviados."
     *     )
     * )
     */
    public function updateInfo(updateInfoRequest $request)
    {
        $user = Auth::user();

        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Put(
     *     path="/api/user/password",
     *     security={{"bearerAuth":{}}},
     *     summary="Update user password",
     *     description="Update the authenticated user's password.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 description="New password for the user"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Senha do usuário atualizada com sucesso.",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação dos dados enviados."
     *     )
     * )
     */
    public function updatePassword(updatePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
