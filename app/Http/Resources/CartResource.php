<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
