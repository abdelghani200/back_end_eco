<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'destroy', 'update', 'store', 'searchProducts', 'recherche');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductCollection::collection(Product::paginate(20));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = new Product;
        $product->name = $request->name;
        $product->image = $request->image;
        $product->description = $request->description;
        $product->categorie_id = $request->categorie_id;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->old_price = $request->old_price;
        $product->code_bare = $request->code_bare;
        $product->discount = $request->discount;
        $product->save();
        return response([
            'data' => new ProductResource($product),
            'message' => 'Product Created'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        $product->update($request->all());
    }

    public function searchProducts(Request $request)
    {

        $name = $request->input('name');
        $price = $request->input('price');
        // dd($price);
        $query = Product::query();

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($price) {

            $query->where('price', 'like', '%' . $price . '%');
        }

        $products = $query->get();
        dd($products);
        if (!$products->count()) {
            return response()->json(['message' => 'No search results']);
        }
        return response()->json(['products' => $products, 'error' => false]);
    }


    public function recherche($name, $prix = null)
    {
        $query = Product::where("name", "like", "%" . $name . "%");
        if ($prix != null) {
            $query->orWhere("prix", $prix);
        }
        $resultats = $query->get();
        return response()->json($resultats);
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // dd($product);
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
