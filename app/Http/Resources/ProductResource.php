<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductSizeResource;
use App\Http\Resources\ProductColorsResource;
use App\Http\Resources\ProductImagesResource;
use Illuminate\Http\Resources\Json\JsonResource;


use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ProductResource", // <-- Nama Skema yang Benar
    title: "ProductResource Schema",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "category_id", type: "integer"),
        new OA\Property(property: "discounted_price", type: "float"),
        new OA\Property(property: "gender", type: "string"),
        new OA\Property(property: "price", type: "float"),
        new OA\Property(property: "sales_number", type: "integer"),
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time"),
        new OA\Property(
            property: "category",
            ref: "#/components/schemas/CategoryResource"
        ),
        new OA\Property(
            property: "images",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/ProductImagesResource")
        ),
        new OA\Property(
            property: "colors",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/ProductColorsResource")
        ),
        new OA\Property(
            property: "sizes",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/ProductSizeResource")
        ),
    ]
)]
class ProductResource extends JsonResource
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
            'gender' => $this->gender,
            'price' => $this->price,
            'sales_number' => $this->sales_number,
            'discounted_price' => $this->discounted_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
            // 'images' => $this->images,
            'images' => $this->whenLoaded('images', function () {
                return ProductImagesResource::collection($this->images);
            }),
            'colors' => $this->whenLoaded('colors', function () {
                return ProductColorsResource::collection($this->colors);
            }),
            'sizes' => $this->whenLoaded('sizes', function () {
                return ProductSizeResource::collection($this->sizes);
            }),
        ];
    }
}
