<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Ongkir;
use Illuminate\Http\Request;

class OngkirController extends Controller
{
    /**
     * Hitung ongkir berdasarkan alamat_id
     */
    public function hitungByAlamat(Request $request)
    {
        $request->validate([
            'alamat_id' => 'required|exists:alamats,id',
            'subtotal' => 'nullable|numeric|min:0',
        ]);

        // Cegah IDOR: alamat harus milik user yang sedang login
        $alamat = Alamat::where('user_id', auth()->id())
            ->where('id', $request->alamat_id)
            ->first();

        if (!$alamat) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan'
            ], 404);
        }

        $ongkir = Ongkir::hitungOngkirDenganSubtotal(
            $alamat->provinsi,
            $alamat->kota,
            $request->input('subtotal', 0)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'provinsi' => $alamat->provinsi,
                'kota' => $alamat->kota,
                'tarif' => $ongkir['tarif'],
                'tarif_formatted' => 'Rp ' . number_format($ongkir['tarif'], 0, ',', '.'),
                'estimasi' => $ongkir['estimasi'],
                'is_free_shipping' => $ongkir['is_free_shipping'] ?? false,
            ]
        ]);
    }

    /**
     * Hitung ongkir berdasarkan nama provinsi
     */
    public function hitungByProvinsi(Request $request)
    {
        $request->validate([
            'provinsi' => 'required|string',
            'kota' => 'nullable|string',
            'subtotal' => 'nullable|numeric|min:0',
        ]);

        $ongkir = Ongkir::hitungOngkirDenganSubtotal(
            $request->provinsi,
            $request->input('kota'),
            $request->input('subtotal', 0)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'provinsi' => $request->provinsi,
                'kota' => $request->input('kota'),
                'tarif' => $ongkir['tarif'],
                'tarif_formatted' => 'Rp ' . number_format($ongkir['tarif'], 0, ',', '.'),
                'estimasi' => $ongkir['estimasi'],
                'is_free_shipping' => $ongkir['is_free_shipping'] ?? false,
            ]
        ]);
    }
}
