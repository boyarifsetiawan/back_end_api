<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductColor extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'title', 'rgb'];
    protected $casts = ['rgb' => 'array'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
