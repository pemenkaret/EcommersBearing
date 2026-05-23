<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KebijakanPrivasi;
use Illuminate\Http\Request;

class KebijakanPrivasiController extends Controller
{
    public function index()
    {
        $kebijakanPrivasi = KebijakanPrivasi::first();
        return view('admin.kebijakan-privasi.index', compact('kebijakanPrivasi'));
    }

    public function edit()
    {
        $kebijakanPrivasi = KebijakanPrivasi::first();
        
        if (!$kebijakanPrivasi) {
            $kebijakanPrivasi = new KebijakanPrivasi();
        }
        
        return view('admin.kebijakan-privasi.edit', compact('kebijakanPrivasi'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.judul' => 'required|string|max:255',
            'items.*.isi' => 'required|string',
            'tanggal_berlaku' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $kebijakanPrivasi = KebijakanPrivasi::first();
        
        if (!$kebijakanPrivasi) {
            $kebijakanPrivasi = new KebijakanPrivasi();
        }

        // Items akan disanitize & encode otomatis lewat mutator KebijakanPrivasi::setKontenAttribute
        $items = array_values($request->items); // Re-index array

        $data = [
            'judul' => $request->judul,
            'konten' => $items,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($kebijakanPrivasi->exists) {
            $kebijakanPrivasi->update($data);
        } else {
            KebijakanPrivasi::create($data);
        }

        return redirect()->route('admin.kebijakan-privasi.index')->with('success', 'Kebijakan Privasi berhasil diupdate.');
    }
}
