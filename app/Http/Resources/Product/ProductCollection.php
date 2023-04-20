<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

        $category = DB::table('categories')->where('id', $this->categorie_id)->first();

        return 
        [
            'id' => $this->id,
            // 'categorie_id' =>$this->categorie_id,
            'name' => $this->name,
            'category' => optional($category)->name,
            // 'category_id' => optional($category)->id,
            'image' => $this->image,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'code_bare' => $this->code_bare,
            'old_price' => $this->old_price,
            'totalPrice' => round((1 -($this->discount/100)) * $this->price, 2),
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star') / $this->reviews->count(), 0) : 'No rating yet',
            'href' =>[
                'link' => route('products.show',$this->id)
            ]
        ];
    }
}
