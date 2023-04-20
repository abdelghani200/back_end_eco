<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategorieRequest;
use App\Models\Categorie;
use Illuminate\Http\Response;
use App\Http\Resources\Categorie\CategorieResource;
use Illuminate\Http\Request;
use App\Http\Resources\Categorie\CategorieCollection;

class CategorieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index',  'destroy', 'showProducts', 'showCategory', 'update', 'store');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategorieResource::collection(Categorie::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // echo 'gggfd';
        // dd($request);
        $categorie = new Categorie;
        $categorie->name = $request->name;
        $categorie->description = $request->description;
        $categorie->image = $request->image;
        $categorie->save();
        return response([
            'data' => new CategorieResource($categorie)
        ], Response::HTTP_CREATED);
    }


    public function showCategory(Categorie $category)
    {
        return response()->json($category);
    }

    public function showProducts(Categorie $category)
    {
        $products = $category->products;

        return response()->json($products);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response([
                'status' => 'error'
            ], 404);
        }

        $categorie->update($request->all());
        // var_dump($request->all());
        return response([
            'status' => 'success'
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $categorie->delete();

        // return response(null, Response::HTTP_NO_CONTENT);

        $categorie = Categorie::findOrFail($id);

        $categorie->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

































/**
 * Display the specified resource.
 */
    // public function show(Categorie $categorie)
    // {
    //     // return $categorie;



    //     // return new CategorieResource($categorie->load('products'));

    //     return CategorieResource::collection($categorie);
    // }

    // public function showCategories(Categorie $categorie)
    // {

    //     return new CategorieResource($categorie);

    // }

    // public function show(Categorie $category)
    // {
    //     $products = $category->products;

    //     return response()->json(['category' => $category, 'products' => $products]);
    // }
