<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CartResource", // <-- Nama Skema yang Benar
    title: "CartResource Schema",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "product_id", type: "integer"),
        new OA\Property(property: "product_title", type: "string"),
        new OA\Property(property: "product_quantity", type: "integer"),
        new OA\Property(property: "product_color", type: "string"),
        new OA\Property(property: "product_size", type: "string"),
        new OA\Property(property: "product_price", type: "number"),
        new OA\Property(property: "total_price", type: "number"),
        new OA\Property(property: "product_image", type: "string"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
    ]
)]
class CartResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_title' => $this->product_title,
            'product_quantity' => $this->product_quantity,
            'product_color' => $this->product_color,
            'product_size' => $this->product_size,
            'product_price' => $this->product_price,
            'total_price' => $this->total_price,
            'product_image' => $this->product_image,
            'created_at' => $this->created_at->format('d M Y, h:i A')
        ];
    }
}
