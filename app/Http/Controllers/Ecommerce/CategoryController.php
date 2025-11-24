<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories.
     *
     * @OA\Get(
     *     path="/get-categories",
     *     summary="Get all categories",
     *     description="Retrieve a list of all categories.",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CategoryResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function getCategories()
    {
        return response([
            'message' => 'Success',
            'results' =>  CategoryResource::collection(Category::all())
        ], 200);
    }
}
