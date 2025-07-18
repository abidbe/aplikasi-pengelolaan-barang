<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\SubKategori;
use Illuminate\Http\Request;

class SubKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika request JSON, return data untuk DataTables
        if ($request->wantsJson()) {
            $subKategoris = SubKategori::with('kategori')
                ->select(['id', 'kategori_id', 'nama', 'batas_harga', 'created_at'])
                ->get();

            return response()->json($subKategoris);
        }

        return view('pages.sub-kategori');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:100',
            'batas_harga' => 'required|numeric|min:0',
        ]);

        SubKategori::create($validated);

        return response()->json(['success' => true, 'message' => 'Sub Kategori berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubKategori $subKategori)
    {
        $subKategori->load('kategori');
        return response()->json($subKategori);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubKategori $subKategori)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:100',
            'batas_harga' => 'required|numeric|min:0',
        ]);

        $subKategori->update($validated);

        return response()->json(['success' => true, 'message' => 'Sub Kategori berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubKategori $subKategori)
    {
        $subKategori->delete();

        return response()->json(['success' => true, 'message' => 'Sub Kategori berhasil dihapus']);
    }

    /**
     * Get kategoris for dropdown
     */
    public function getKategoris()
    {
        $kategoris = Kategori::select(['id', 'kode', 'nama'])->get();
        return response()->json($kategoris);
    }
}
