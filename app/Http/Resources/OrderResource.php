<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "OrderResource", // <-- Nama Skema yang Benar
    title: "OrderResource Schema",
    properties: [
        new OA\Property(property: "order_id", type: "integer"),
        new OA\Property(property: "user", ref: "#/components/schemas/UserResource"),
        new OA\Property(property: "code", type: "string"),
        new OA\Property(property: "shippingAddress", type: "string"),
        new OA\Property(property: "itemCount", type: "integer"),
        new OA\Property(property: "totalPrice", type: "number"),
        new OA\Property(property: "createdDate", type: "string", format: "date-time"),
        new OA\Property(
            property: "products",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/OrderedProductsResource")
        ),
        new OA\Property(
            property: "orderStatus",
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "done", type: "boolean"),
                    new OA\Property(property: "createdDate", type: "string", format: "date-time"),
                ]
            )
        ),
    ]
)]
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
