<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UserCreateRequest",
 *     type="object",
 *     required={"last_name", "email", "first_name", "role_id", "password"},
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="First name of the user"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         description="Last name of the user"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Email address of the user"
 *     ),
 *     @OA\Property(
 *         property="role_id",
 *         type="integer",
 *         description="ID of the user's role"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="Password for the user account"
 *     )
 * )
 */
class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('edit', 'users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'role_id' => 'required|integer',
            'password' => 'required|string',
            'email' => 'required|email',
        ];
    }
}
