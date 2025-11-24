<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "OrderedProductsResource", // <-- Nama Skema yang Benar
    title: "OrderedProductsResource Schema",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "order_id", type: "integer"),
        new OA\Property(property: "product_id", type: "integer"),
        new OA\Property(property: "product_title", type: "string"),
        new OA\Property(property: "product_quantity", type: "integer"),
        new OA\Property(property: "total_price", type: "number", format: "float"),
        new OA\Property(property: "product_price", type: "number", format: "float"),
        new OA\Property(property: "product_color", type: "string"),
        new OA\Property(property: "product_image", type: "string"),
        new OA\Property(property: "product_size", type: "string"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
    ]
)]
class OrderedProductsResource extends JsonResource
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
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_title' => $this->title,
            'product_quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'product_price' => $this->product_price,
            'product_color' => $this->color,
            'product_image' => $this->image,
            'product_size' => $this->size,
            'created_at' => $this->created_at->format('d M Y, h:i A')
        ];
    }
}
