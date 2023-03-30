<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:products',
            'description' => 'required',
            'price' => 'required|max:10',
            'stock' => 'required|max:6',
            'code_bare' => 'required|max:10',
            'old_price' => 'required|max:10',
            'discount' => 'required|max:2',
        ];
    }
}
