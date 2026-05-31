<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->is_admin ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:plans,name',
            'slug' => 'required|string|max:255|unique:plans,slug',
            'category' => 'required|in:personal,family,business',
            'tier' => 'required|in:free,low,mid,high',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*.name' => 'required|string|max:255',
            'features.*.description' => 'nullable|string',
            'features.*.icon' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama paket harus diisi',
            'name.unique' => 'Nama paket sudah ada',
            'slug.required' => 'Slug harus diisi',
            'slug.unique' => 'Slug sudah ada',
            'category.required' => 'Kategori harus dipilih',
            'price_monthly.required' => 'Harga bulanan harus diisi',
        ];
    }
}
