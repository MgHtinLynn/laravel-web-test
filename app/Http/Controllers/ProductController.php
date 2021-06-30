<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Models\ProductQuantity;
use App\Models\Products;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $products = Products::with('productQuantity')->get();
        return ProductResource::collection($products);
    }

    /**
     * @param  ProductCreateRequest  $request
     * @return JsonResponse
     */
    public function store(ProductCreateRequest $request): JsonResponse
    {
        //check product exist
        $productExist = Products::with('productQuantity')
            ->where([
                'name' => $request->input('name'),
                'price' => $request->input('price')
            ])->first();

        if ($productExist) {
            // if product exist, add increment into quantity
            $result = $productExist->productQuantity->increment('quantity');
        } else {
            // if new, create product quantity first
            $productQuantity = ProductQuantity::create(['quantity' => 1]);
            // if new, create product
            $product = new Products($request->only(['name', 'description', 'price']));
            $result = $productQuantity->products()->save($product);
        }
        // for success
        if ($result) {
            return response()->json([
                'message' => 'successfully created product',
                'status' => 201
            ]);
        }

        // for fail
        return response()->json([
            'message' => 'something wrong',
            'status' => 400
        ], 400);
    }
}
