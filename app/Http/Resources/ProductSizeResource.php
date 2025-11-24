<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ProductSizeResource", // <-- Nama Skema yang Benar
    title: "ProductSizeResource Schema",
    properties: [
        new OA\Property(property: "product_id", type: "integer"),
        new OA\Property(property: "size", type: "string"),
    ]
)]
class ProductSizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'size' => $this->size,
        ];
    }
}
