<?php


namespace App\Http\Controllers\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Back End API",
    description: "Ecommerce-app endpoint"
)]
#[OA\Server(
    url: "http://localhost:8000/api",
    description: "Development API Server"
)]
class OpenApi
{
    // Class ini kosong, hanya berfungsi sebagai penampung global attributes.
}
