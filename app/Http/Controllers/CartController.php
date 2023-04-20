<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {

        $accessToken = PersonalAccessToken::where('token', $request->bearerToken())->first();

        $token = $accessToken->token;

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide.',
            ], 401);
        }

        $user = User::find($accessToken->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur n\'est pas connecté.',
            ], 401);
        }

        $data = $request->json()->all();

        // dd($data);

        // Recherchez le panier de l'utilisateur
        $cart = Cart::where('user_id', $data['user_id'])
            ->where('product_id', $data['product_id'])
            ->first();

        // Si le panier n'existe pas, on le créé

        // Si l'utilisateur n'a pas encore de panier, créez-en un
        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $data['user_id'];
            $cart->product_id = $data['product_id'];
            $cart->quantity = 0;
        }

        // Incrémentez la quantité
        $cart->quantity += $data['quantity'];
        // $cart->stock -= $data['quantity'];
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Le produit a été ajouté au panier.',
            'cart' => $cart
        ]);
    }

    public function getCart(Request $request)
    {
        // echo 'gggd';
        $accessToken = PersonalAccessToken::where('token', $request->bearerToken())->first();

        // dd($accessToken);
        $token = $accessToken->token;

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide.',
            ], 401);
        }

        $user = User::find($accessToken->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur n\'est pas connecté.',
            ], 401);
        }

        $carts = Cart::where('user_id', $user->id)->with('products')->get();

        // dd($carts[0]->quantity);
        if (!$carts->count()) {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur n\'a pas encore effectué d\'achats.',
            ]);
        }

        $purchasedProducts = [];

        foreach ($carts as $order) {
            foreach ($order->products as $product) {
                $purchasedProduct = $product;
                $purchasedProduct['quantity'] = $order->quantity;
                $purchasedProducts[] = $product;
            }
        }

        return response()->json([
            'number_product' => count($purchasedProducts),
            'user' => $user,
            'success' => true,
            'message' => 'Les produits achetés par l\'utilisateur ont été récupérés avec succès.',
            'data' => $purchasedProducts,
        ]);
    }


    public function updateQuantity(Request $request, $product_id)
    {
        // $cart = Cart::find($id);
        $cart = Cart::where('product_id', $product_id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Le produit n\'existe pas dans le panier.',
            ], 404);
        }

        $cart->quantity = $request->input('quantity');
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'La quantité du produit a été mise à jour.',
            'cart' => $cart
        ]);
    }

    public function removeFromCart(Request $request, $product_id)
    {
        $cart = Cart::where('product_id', $product_id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Le produit n\'existe pas dans le panier.',
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Le produit a été supprimé du panier.',
        ]);
    }

    // public function emptyCart(Request $request)
    // {
    //     $accessToken = PersonalAccessToken::where('token', $request->bearerToken())->first();

    //     $token = $accessToken->token;

    //     if (!$token) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Token invalide.',
    //         ], 401);
    //     }

    //     $user = User::find($accessToken->user_id);

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'L\'utilisateur n\'est pas connecté.',
    //         ], 401);
    //     }

    //     $carts = Cart::where('user_id', $user->id)->get();

    //     if (!$carts->count()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'L\'utilisateur n\'a pas encore effectué d\'achats.',
    //         ]);
    //     }

    //     foreach ($carts as $cart) {
    //         $cart->delete();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Le panier a été vidé avec succès.',
    //     ]);
    // }

    public function checkout(Request $request)
    {
        $accessToken = PersonalAccessToken::where('token', $request->bearerToken())->first();

        $token = $accessToken->token;

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide.',
            ], 401);
        }

        $user = User::find($accessToken->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur n\'est pas connecté.',
            ], 401);
        }

        $carts = Cart::where('user_id', $user->id)->get();

        if (!$carts->count()) {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur n\'a pas encore effectué d\'achats.',
            ]);
        }

        foreach ($carts as $cart) {
            $cart->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Le panier a été vidé avec succès.',
        ]);
    }

}
