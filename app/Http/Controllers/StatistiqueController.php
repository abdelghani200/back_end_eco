<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $categorieCount = Categorie::count();
        // $totalStars = Review::sum('star');
        // $totalReviews = Review::sum('review');
        
        $totalprice = Order::sum('total');
        $categories = Categorie::withCount('products')->get();
        $categoryProductsCount = [];

        foreach ($categories as $category) {
            // $count = Product::where('categorie_id', $category->id)->count();
            $categoryProductsCount['category_' . $category->id] = [
                'name' => $category->name,
                'nb_p' => $category->products_count
            ];
        }

        // $categoryProductsCount = Product::where('categorie_id', 1)->count();
        $commentRatingsCount = Review::count();

        $statistics = [
            'productsCount' => $productsCount,
            'ordersCount' => $ordersCount,
            'categorieCount' => $categorieCount,
            'categoryProductsCount' => $categoryProductsCount,
            'commentRatingsCount' => $commentRatingsCount,
            'prixCount' => $totalprice,
            // 'totalStars' => $totalStars,
            // 'totalReviews' => $totalReviews,
        ];
        // $statistics = array_merge($statistics, $categoryProductsCount);

        return response()->json($statistics);
    }
}
