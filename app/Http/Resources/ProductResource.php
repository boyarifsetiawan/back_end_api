<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductSizeResource;
use App\Http\Resources\ProductColorsResource;
use App\Http\Resources\ProductImagesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 1. Dapatkan base URL dari aplikasi Laravel Anda (misalnya http://127.0.0.1:8000)
        $baseUrl = config('app.url'); // Pastikan APP_URL di .env sudah benar

        // 2. Map array nama file menjadi URL penuh
        $fullImageUrls = collect($this->images)->map(function ($fileName) use ($baseUrl) {
            // Menggunakan helper asset() atau URL::asset() di sini adalah praktik yang baik
            // Namun, untuk akses dari Flutter, kita konstruksi manual lebih sederhana:
            return "$baseUrl/storage/products/$fileName";
        });

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
