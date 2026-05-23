<?php

namespace App\Http\Requests\Pelanggan;

use App\Models\MetodePembayaran;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Get list of active metode pembayaran IDs
        $metodePembayaranIds = MetodePembayaran::where('is_active', true)->pluck('id')->toArray();

        return [
            'alamat_id' => ['required', 'exists:alamats,id'],
            'metode_pembayaran_id' => ['required', 'exists:metode_pembayarans,id', function ($attribute, $value, $fail) use ($metodePembayaranIds) {
                if (!in_array($value, $metodePembayaranIds)) {
                    $fail('Metode pembayaran tidak valid atau tidak aktif.');
                }
            }],
            // Catatan: ongkir TIDAK diterima dari client. Selalu dihitung ulang
            // di server (CheckoutController) memakai Ongkir::hitungOngkirDenganSubtotal.
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'alamat_id.required' => 'Alamat pengiriman wajib dipilih',
            'alamat_id.exists' => 'Alamat tidak valid',
            'metode_pembayaran_id.required' => 'Metode pembayaran wajib dipilih',
            'metode_pembayaran_id.exists' => 'Metode pembayaran tidak valid',
            'catatan.max' => 'Catatan maksimal 500 karakter',
        ];
    }
}
