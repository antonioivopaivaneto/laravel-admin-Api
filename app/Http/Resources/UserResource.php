<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     required={"id", "first_name", "last_name", "email", "role"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do usuário"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="Primeiro nome do usuário"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         description="Último nome do usuário"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Email do usuário"
 *     ),
 *     @OA\Property(
 *         property="role",
 *         type="string",
 *         description="Função ou cargo do usuário"
 *     ),
 * )
 */

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'first_name' =>$this->first_name,
            'last_name' =>$this->last_name,
            'email' =>$this->iemaild,
            'role' =>$this->role,
        ];
    }
}
