<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasis = Lokasi::where('aktif', 'Y')
            ->latest()
            ->paginate(10);

        return view('pages.admin.lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        return view('pages.admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
        ]);

        Lokasi::create([
            'nama_lokasi' => $request->nama_lokasi,
            'aktif' => 'Y',
        ]);

        return redirect()
            ->route('lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Lokasi $lokasi)
    {
        return view('pages.admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
        ]);

        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
        ]);

        return redirect()
            ->route('lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Lokasi $lokasi)
    {
        // Soft Delete Manual
        $lokasi->aktif = 'N';
        $lokasi->save();

        return redirect()
            ->route('lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus.');
    }
}