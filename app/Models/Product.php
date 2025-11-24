<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'discounted_price', 'gender', 'price', 'sales_number', 'title', 'created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at'   => 'datetime:Y-m-d H:i:s',
        'price' => 'float',
        'discounted_price' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class, 'product_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function favoriteBy()
    {
        return $this->belongsToMany(User::class, 'favorite_products');
    }
}
