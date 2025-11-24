<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ProductImagesResource", // <-- Nama Skema yang Benar
    title: "ProductImagesResource Schema",
    properties: [
        new OA\Property(property: "url", type: "string"),
    ]
)]
class ProductImagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->url,
        ];
    }
}
