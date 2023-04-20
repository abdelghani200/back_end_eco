<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $category = DB::table('categories')->where('id', $this->categorie_id)->first();

        return [
            'name' => $this->name,
            'image' => $this->image,
            'category' => optional($category)->name,
            'category_id' => optional($category)->id,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock == 0 ? 'Out of stock' : $this->stock,
            'code_bare' => $this->code_bare,
            'discount' => $this->discount,
            'totalPrice' => round((1 -($this->discount/100)) * $this->price, 2),
            'old_price' => $this->old_price,
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star') / $this->reviews->count(), 0) : 'No rating yet',
            'href' => [
                'reviews' => route('reviews.index', $this->id)
            ]
        ];
    }
}
