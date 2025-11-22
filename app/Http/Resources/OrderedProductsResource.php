<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
