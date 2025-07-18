<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika request JSON, return data untuk DataTables
        if ($request->wantsJson()) {
            $kategoris = Kategori::select(['id', 'kode', 'nama', 'created_at'])
                ->get();

            return response()->json($kategoris);
        }

        return view('pages.kategori');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:kategoris',
            'nama' => 'required|string|max:100',
        ]);

        Kategori::create($validated);

        return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        return response()->json($kategori);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:kategoris,kode,' . $kategori->id,
            'nama' => 'required|string|max:100',
        ]);

        $kategori->update($validated);

        return response()->json(['success' => true, 'message' => 'Kategori berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus']);
    }
}
