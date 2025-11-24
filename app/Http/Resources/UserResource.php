<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;


#[OA\Schema(
    schema: "UserResource", // <-- Nama Skema yang Benar
    title: "UserResource Schema",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "first_name", type: "string"),
        new OA\Property(property: "last_name", type: "string"),
        new OA\Property(property: "email", type: "string"),
        new OA\Property(property: "image", type: "string"),
        new OA\Property(property: "gender", type: "number"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
    ]
)]
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
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'image' => $this->image,
            'created_at' => $this->created_at,
        ];
    }
}
