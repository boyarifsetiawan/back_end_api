<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
