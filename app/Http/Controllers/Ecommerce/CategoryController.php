<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Category"},
     *     summary="Get all categories",
     *     description="Mengambil semua kategori dan mengembalikannya dalam bentuk CategoryResource collection.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil daftar kategori",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Category")
     *             )
     *         )
     *     )
     * )
     *
     * Mengambil semua data kategori.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        return response([
            'message' => 'Success',
            'results' =>  CategoryResource::collection(Category::all())
        ], 200);
    }
}
