<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
            'sku' => is_string($this->sku) ? strtoupper(trim($this->sku)) : $this->sku,
            'description' => is_string($this->description) ? trim($this->description) : $this->description,
        ]);
    }

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'sku' => ['required', 'string', 'max:64', 'alpha_dash', Rule::unique('products', 'sku')],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock_qty' => ['required', 'integer', 'min:0', 'max:1000000'],
            'status' => ['required', Rule::in(Product::statuses())],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sku.unique' => 'This SKU is already in use. Please enter a different SKU.',
            'status.in' => 'Status must be one of: draft, active, archived.',
            'stock_qty.min' => 'Stock quantity cannot be negative.',
        ];
    }
}
