<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'code' => $this->code,
            'shippingAddress' => $this->shipping_address,
            'itemCount' => $this->item_count,
            'totalPrice' => $this->total_price,
            'createdDate' => $this->created_at->format('d M Y, h:i A'),
            'products' => OrderedProductsResource::collection($this->whenLoaded('orderedProducts')),
            'orderStatus' => $this->statuses->map(fn($s) => [
                'title' => $s->title,
                'done' => $s->done,
                'createdDate' => $s->created_at->format('d M Y, h:i A')
            ]),
        ];
    }
}
