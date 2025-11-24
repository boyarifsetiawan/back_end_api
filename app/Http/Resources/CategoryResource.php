<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CategoryResource", // <-- Nama Skema yang Benar
    title: "CategoryResource Schema",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "image", type: "string"),
        new OA\Property(
            property: "products",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/ProductResource")
        ),
    ]
)]
class CategoryResource extends JsonResource
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
            'title' => $this->title,
            'image' => $this->image,
            'products' => $this->whenLoaded('products', function () {
                return Product::collection($this->products);
            })
        ];
    }
}
