<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\StatistiqueController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//         return $request->user();
// }); 

// Route::get('/user', function (Request $request){ return $request->user()->id; })->middleware('auth:api');


Route::controller(UserController::class)->group(function () {
    Route::post('login', 'loginUser')->name('login');
    Route::post('register', 'registerUser');
});

Route::controller(UserController::class)->group(function () {
    Route::get('user', 'getUserDetail');
    Route::get('users', 'getAllUsers');
    Route::get('logout', 'userLogout');
})->middleware('auth:api');


// ->middleware('auth:api')



Route::apiResource('/products', ProductController::class);


Route::group(['prefix' => 'products'], function () {

    Route::apiResource('/{product}/reviews', ReviewController::class);
})->middleware('auth:api');

// ->middleware('auth:api')

// Route::middleware('auth:api')->group(function () {

// });

// Route::middleware('auth:api')->post('/addToCart', [CartController::class, 'addToCart']);

Route::get('cart', [CartController::class, 'getCart'])->middleware('auth:api');

Route::controller(CartController::class)->group(function () {
    // Route::middleware('auth:api')->get('cart',[CartController::class, 'getCart']);
    Route::get('cart',[CartController::class, 'getCart']);
    Route::post('addToCart', 'addToCart');
    // Route::delete('cart', 'removeFromCart');
})->middleware('auth:api');

// Route::middleware('auth:api')->get('order/{id}',[OrderController::class, 'getOrder']);

// Route::middleware('auth:api')->get('orders',[Ordeontroller::class, 'getAllOrders']);

Route::middleware('auth:api')->post('orders',[OrderController::class, 'createOrder']);

Route::controller(OrderController::class)->group(function() {
        // Route::middleware('auth:api')->get('orders', [OrderController::class, 'getAllOrders']);
        Route::get('orders', 'getAllOrders');
        Route::post('orders', 'createOrder');
        Route::delete('orders/{id}', 'deleteOrder');
    })->middleware('auth:api');


// Route::get('/products/search', [ProductController::class, 'searchProducts']);
Route::get('search/{name}/{prix?}', [ProductController::class, 'recherche']);



Route::apiResource('/categories', CategorieController::class);


Route::get('/categories/{category}', [CategorieController::class, 'showCategory'])->name('categories.showCategory');
Route::get('/categories/{category}/products', [CategorieController::class, 'showProducts'])->name('categories.showProducts');

Route::put('/orders/{id}/archived', [OrderController::class, 'archived']);

    
Route::put('/orders/{id}/validate', [OrderController::class, 'validateOrder']);
Route::put('/orders/{id}/deliver', [OrderController::class, 'deliverOrder']);

Route::put('/cart/{id}', [CartController::class, 'updateQuantity']);

Route::delete('/cart/{id}', [CartController::class, 'removeFromCart']);

Route::delete('/cart', [CartController::class, 'checkout']);

Route::get('/orders/{id}/produits',  [OrderController::class, 'getProduitsByOrderId']);

Route::get('/statistics', [StatistiqueController::class, 'index']);

// Route pour obtenir les produits les plus vendus
Route::get('/best-selling-products', [OrderController::class, 'getBestSellingProducts']);




































// Route::prefix('api')->group(function () {
//     Route::middleware('auth:api')->group(function () {
//         Route::post('order', [OrderController::class, 'createOrder']);
//         Route::get('order/{id}', [OrderController::class, 'getOrder']);
//         // Ajoutez d'autres routes API ici
//     });
// });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    






    
    
    
    
    
    
    
    
    
    // Route::group(['prefix' => 'categories'], function () {

    //     Route::apiResource('/{categorie}/products', ProductController::class);
    // });

    // Route::get('/categories/{category}', [CategorieController::class, 'showCategories'])->name('categories.show');




// });

// Route::get('/categories/{categorie}', [CategorieController::class, 'show'])->name('categories.products.index');


// Route::get('/categories/{category}', [CategorieController::class, 'show']);



