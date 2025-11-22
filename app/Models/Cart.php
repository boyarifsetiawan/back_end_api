<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Cart", // <-- Nama Skema yang Benar
    title: "Cart Schema",
    properties: [
        new OA\Property(property: "user_id", type: "integer"),
        new OA\Property(property: "product_id", type: "integer"),
        new OA\Property(property: "product_title", type: "string"),
        new OA\Property(property: "product_quantity", type: "integer"),
        new OA\Property(property: "product_color", type: "string"),
        new OA\Property(property: "product_size", type: "string"),
        new OA\Property(property: "product_price", type: "float"),
        new OA\Property(property: "total_price", type: "float"),
        new OA\Property(property: "product_image", type: "string"),
        // ... properti lainnya
    ]
)]
class Cart extends Model
{
    protected $fillable  = [
        'user_id',
        'product_id',
        'product_title',
        'product_quantity',
        'product_color',
        'product_size',
        'product_price',
        'total_price',
        'product_image'
    ];

    protected $casts = [
        'product_price' => 'float',
        'total_price' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
