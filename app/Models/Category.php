<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use OpenApi\Attributes as OA;


#[OA\Schema(
    schema: "Category", // <-- Nama Skema yang Benar
    title: "Category Schema",
    properties: [
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "image", type: "string"),
    ]
)]
class Category extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'image'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
