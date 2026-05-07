<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
            'description' => is_string($this->description) ? trim($this->description) : $this->description,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock_qty' => ['required', 'integer', 'min:0', 'max:1000000'],
            'status' => ['required', Rule::in(Product::statuses())],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status must be one of: draft, active, archived.',
            'stock_qty.min' => 'Stock quantity cannot be negative.',
        ];
    }
}
