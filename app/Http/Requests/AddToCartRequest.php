<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            // 1. Validasi Product ID
            'product_id' => [
                'required',
                'integer',
                // Memastikan ID produk harus ada di tabel 'products'
                Rule::exists('products', 'id'),
            ],

            // 2. Validasi Kuantitas
            'product_quantity' => [
                'required',
                'integer',
                'min:1', // Kuantitas minimal harus 1
            ],

            // 3. Validasi Warna (Opsional/Jika Diperlukan)
            'product_color' => [
                'nullable', // Boleh kosong/null
                'string',
                'max:50',
                // Contoh jika warna harus ada di tabel 'product_colors'
                // Rule::exists('product_colors', 'color_name')->where('product_id', $this->product_id),
            ],

            // 4. Validasi Ukuran (Opsional/Jika Diperlukan)
            'product_size' => [
                'nullable',
                'string',
                'max:50',
            ],

            // 5. Validasi Harga (Pastikan Harga Terdapat dalam Request, Walaupun Dihitung Ulang di Backend)
            // Penting: Harga seharusnya diambil dari database, BUKAN dari input user.
            // Namun, jika Anda memerlukan validasi format:
            'product_price' => [
                'required',
                'numeric',
            ],
            'total_price' => [
                'required',
                'numeric',
            ],

            // 6. Validasi URL Gambar
            'product_image' => [
                'url',
            ],
            'product_title' => [
                'string',
            ],
        ];
    }

    /**
     * Kustomisasi pesan error (Opsional).
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'ID produk wajib diisi.',
            'product_id.exists' => 'Produk tidak ditemukan dalam database.',
            'product_quantity.min' => 'Kuantitas minimal adalah 1.',
        ];
    }
}
