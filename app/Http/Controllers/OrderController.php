<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('getAllOrders', 'createOrder', 'deleteOrder', 'deliverOrder', 'validateOrder', 'getBestSellingProducts', 'getProduitsByOrderId');
    }


    public function createOrder(Request $request)
    {
        // Validate the form inputs
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantite' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'statut' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create a new order for the connected user
        $order = new Order;
        $order->user_id = $request->user_id;
        $order->total = $request->total;
        $order->statut = $request->statut;
        $order->save();

        // Attach each product to the order
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $quantity = $item['quantite'];
            $order->products()->attach($product, ['quantite' => $quantity]);
        }

        // Return the created order as a JSON response
        $data = [
            'user_id' => $order->user_id,
            'items' => $order->products()->get(),
            'total' => $order->total,
            'statut' => $order->statut,
        ];

        return response()->json($data, 201);
    }


    public function getAllOrders(Request $request)
    {
        // Récupérer toutes les commandes avec leurs produits associés
        $orders = Order::with('products', 'user')->get();

        // dd($orders);
        // Ajouter une colonne pour le nombre de produits achetés à chaque commande
        $orders->transform(function ($order) {
            $order->total_products = $order->products->count();
            $order->user_name = $order->user->name;
            return $order;
        });
        // Retourner les commandes sous forme de réponse JSON
        return response()->json([
            'success' => true,
            'message' => 'Toutes les commandes ont été récupérées avec succès.',
            'data' => $orders,
        ]);
    }

    public function getProduitsByOrderId($id)
    {
        $order = Order::findOrFail($id);
        $produits = $order->products;
        // dd($produits);
        return response()->json($produits);
    }


    public function validateOrder($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        $order->statut = 'validated';
        // Ajouter la date et l'heure actuelles dans le champ "time_validation" de la commande
        $order->time_validation = now();
        $order->save();
        return response()->json(['message' => 'Order validated'], 200);
    }

    public function deliverOrder($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        $order->statut = 'delivered';
        $order->time_delevration = now();
        $order->save();
        return response()->json(['message' => 'Order delivered'], 200);
    }

    public function getBestSellingProducts()
    {
        // Récupérer tous les produits avec leurs commandes associées
        $products = Product::with('orders')->get();

        // Filtrer les produits qui ont été vendus au moins 5 fois
        $products = $products->filter(function ($product) {
            $total_sales = $product->orders()->sum('quantite');
            return $total_sales >= 2;
        });

        // Trier les produits en fonction du nombre total de produits vendus
        $products = $products->sortByDesc(function ($product) {
            return $product->orders()->sum('quantite');
        });

        // Retourner les produits les plus vendus
        return response()->json([
            'success' => true,
            'message' => 'Les produits les plus vendus ont été récupérés avec succès.',
            'data' => $products,
        ]);
    }



    public function deleteOrder($id)
    {
        // Find the order
        $order = Order::find($id);

        // If the order doesn't exist, return a 404 error
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Delete the order
        $order->delete();

        // Return a success message
        return response()->json(['message' => 'Order deleted'], 200);
    }
}
