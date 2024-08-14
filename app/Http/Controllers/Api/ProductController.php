<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view','products');

        $products = Product::paginate(10);
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        Gate::authorize('edit','products');




        $product = Product::create($request->only('title','description','image','price'));

        return response()->json($product, Response::HTTP_CREATED);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('view','products');

        $product = Product::find($id);
        return new  ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {

        Gate::authorize('edit','products');


        $product = product::find($id);

        $product->update($request->only('title','description','image','price'));


        return response()->json($product, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('edit','products');

        Product::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
