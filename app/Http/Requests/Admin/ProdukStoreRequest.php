<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProdukStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'harga' => $this->normalizePrice($this->input('harga')),
            'harga_diskon' => $this->normalizePrice($this->input('harga_diskon')),
            'stok' => $this->normalizeInteger($this->input('stok')),
            'min_stok' => $this->normalizeInteger($this->input('min_stok')),
        ]);
    }

    private function normalizePrice(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (is_numeric($value)) {
            return $value;
        }

        $normalized = preg_replace('/[^\d]/', '', (string) $value);

        return $normalized === '' ? null : $normalized;
    }

    // Strip leading zero pada integer; "007" -> "7", "0" tetap "0", non-digit ditolak ke validator
    private function normalizeInteger(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $str = (string) $value;
        if (! preg_match('/^\d+$/', $str)) {
            return $value;
        }

        return ltrim($str, '0') === '' ? '0' : ltrim($str, '0');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'merk_id' => ['required', 'exists:merks,id'],
            'nama' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', 'unique:produks,sku'],
            'harga' => ['required', 'numeric', 'min:0'],
            'harga_diskon' => ['nullable', 'numeric', 'min:0', 'lt:harga'],
            'stok' => ['required', 'integer', 'min:0'],
            'min_stok' => ['required', 'integer', 'min:0'],
            'berat' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'inner_diameter' => ['nullable', 'numeric', 'min:0'],
            'outer_diameter' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'material' => ['nullable', 'string', 'max:100'],
            'seal_type' => ['nullable', 'string', 'max:100'],
            'cage_type' => ['nullable', 'string', 'max:100'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'merk_id.required' => 'Merk wajib dipilih',
            'merk_id.exists' => 'Merk tidak valid',
            'nama.required' => 'Nama produk wajib diisi',
            'sku.unique' => 'SKU sudah digunakan',
            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga_diskon.lt' => 'Harga diskon harus lebih kecil dari harga normal',
            'stok.required' => 'Stok wajib diisi',
            'stok.integer' => 'Stok harus berupa angka bulat',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Gambar harus berformat: jpeg, png, jpg, atau webp',
            'images.*.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
