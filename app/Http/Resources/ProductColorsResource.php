<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ProductColorsResource", // <-- Nama Skema yang Benar
    title: "ProductColorsResource Schema",
    properties: [
        new OA\Property(property: "product_id", type: "integer"),
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "rgb", type: "string"),
    ]
)]
class ProductColorsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'product_id' => $this->product_id,
                'title' => $this->title,
                'rgb' => $this->rgb
            ];
    }
}
