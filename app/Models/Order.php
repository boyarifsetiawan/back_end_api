<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Order", // <-- Nama Skema yang Benar
    title: "Order Schema",
    properties: [
        new OA\Property(property: "user_id", type: "integer"),
        new OA\Property(property: "code", type: "integer"),
        new OA\Property(property: "shipping_address", type: "string"),
        new OA\Property(property: "item_count", type: "integer"),
        new OA\Property(property: "total_price", type: "string"),
        // ... properti lainnya
    ]
)]
class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'code', 'shipping_address', 'item_count', 'total_price', 'created_at', 'updated_at'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at'   => 'datetime:Y-m-d H:i:s',
        'total_price' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function orderedProducts()
    {
        return $this->hasMany(OrderedProduct::class, 'order_id');
    }
    public function statuses()
    {
        return $this->hasMany(OrderStatus::class, 'order_id');
    }
}
