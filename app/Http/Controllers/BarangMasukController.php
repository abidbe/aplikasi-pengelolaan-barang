<?php

namespace App\Http\Controllers;

use App\Exports\BarangMasukExport;
use App\Models\BarangMasuk;
use App\Models\BarangMasukItem;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            // Include items in the response for table display
            $query = BarangMasuk::with(['operator', 'subKategori.kategori', 'items']);

            // Filter by user role
            if (auth()->user()->role === 'operator') {
                $query->where('operator_id', auth()->id());
            }

            // Apply filters
            if ($request->filled('kategori_id')) {
                $query->whereHas('subKategori', function ($q) use ($request) {
                    $q->where('kategori_id', $request->kategori_id);
                });
            }

            if ($request->filled('sub_kategori_id')) {
                $query->where('sub_kategori_id', $request->sub_kategori_id);
            }

            if ($request->filled('tahun')) {
                $query->whereYear('created_at', $request->tahun);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('asal_barang', 'like', "%{$search}%")
                        ->orWhereHas('subKategori', function ($sq) use ($search) {
                            $sq->where('nama', 'like', "%{$search}%");
                        });
                });
            }

            // Sorting
            if ($request->filled('sort')) {
                $sort = $request->sort;
                $direction = $request->get('direction', 'asc');

                switch ($sort) {
                    case 'tanggal':
                        $query->orderBy('created_at', $direction);
                        break;
                    case 'asal_barang':
                        $query->orderBy('asal_barang', $direction);
                        break;
                    case 'total_harga':
                        $query->orderBy('total_harga', $direction);
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $barangMasuks = $query->get();
            return response()->json($barangMasuks);
        }

        return view('pages.barang-masuk');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $operators = User::where('role', 'operator')->get();

        return view('pages.create-barang-masuk', compact('kategoris', 'operators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:users,id',
            'sub_kategori_id' => 'required|exists:sub_kategoris,id',
            'asal_barang' => 'required|string|max:200',
            'nomor_surat' => 'nullable|string|max:100',
            'lampiran' => 'nullable|file|mimes:doc,docx,zip|max:10240',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:200',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:40',
            'items.*.tgl_expired' => 'nullable|date',
        ]);

        // Check if operator role can only create for themselves
        if (auth()->user()->role === 'operator') {
            $validated['operator_id'] = auth()->id();
        }

        // Calculate total harga
        $totalHarga = 0;
        foreach ($validated['items'] as $item) {
            $totalHarga += $item['harga'] * $item['jumlah'];
        }

        // Validate against batas harga
        $subKategori = SubKategori::find($validated['sub_kategori_id']);
        if ($totalHarga > $subKategori->batas_harga) {
            return response()->json([
                'success' => false,
                'message' => 'Total harga melebihi batas harga sub kategori (Rp ' . number_format($subKategori->batas_harga, 2, ',', '.') . ')'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Handle file upload
            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $lampiranPath = $request->file('lampiran')->store('lampirans', 'public');
            }

            // Create barang masuk
            $barangMasuk = BarangMasuk::create([
                'operator_id' => $validated['operator_id'],
                'sub_kategori_id' => $validated['sub_kategori_id'],
                'asal_barang' => $validated['asal_barang'],
                'nomor_surat' => $validated['nomor_surat'],
                'lampiran' => $lampiranPath,
                'total_harga' => $totalHarga,
            ]);

            // Create items
            foreach ($validated['items'] as $item) {
                BarangMasukItem::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'nama_barang' => $item['nama_barang'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                    'total' => $item['harga'] * $item['jumlah'],
                    'tgl_expired' => $item['tgl_expired'],
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Barang masuk berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($lampiranPath) {
                Storage::disk('public')->delete($lampiranPath);
            }
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk)
    {
        // Check authorization
        if (auth()->user()->role === 'operator' && $barangMasuk->operator_id !== auth()->id()) {
            abort(403);
        }

        $kategoris = Kategori::all();
        $operators = User::where('role', 'operator')->get();
        $barangMasuk->load(['items', 'subKategori.kategori']);

        return view('pages.edit-barang-masuk', compact('barangMasuk', 'kategoris', 'operators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        // Check authorization
        if (auth()->user()->role === 'operator' && $barangMasuk->operator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'operator_id' => 'required|exists:users,id',
            'sub_kategori_id' => 'required|exists:sub_kategoris,id',
            'asal_barang' => 'required|string|max:200',
            'nomor_surat' => 'nullable|string|max:100',
            'lampiran' => 'nullable|file|mimes:doc,docx,zip|max:10240',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:200',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:40',
            'items.*.tgl_expired' => 'nullable|date',
        ]);

        if (auth()->user()->role === 'operator') {
            $validated['operator_id'] = auth()->id();
        }

        // Calculate total harga
        $totalHarga = 0;
        foreach ($validated['items'] as $item) {
            $totalHarga += $item['harga'] * $item['jumlah'];
        }

        // Validate against batas harga
        $subKategori = SubKategori::find($validated['sub_kategori_id']);
        if ($totalHarga > $subKategori->batas_harga) {
            return response()->json([
                'success' => false,
                'message' => 'Total harga melebihi batas harga sub kategori (Rp ' . number_format($subKategori->batas_harga, 2, ',', '.') . ')'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Handle file upload
            $lampiranPath = $barangMasuk->lampiran;
            if ($request->hasFile('lampiran')) {
                if ($lampiranPath) {
                    Storage::disk('public')->delete($lampiranPath);
                }
                $lampiranPath = $request->file('lampiran')->store('lampirans', 'public');
            }

            // Update barang masuk
            $barangMasuk->update([
                'operator_id' => $validated['operator_id'],
                'sub_kategori_id' => $validated['sub_kategori_id'],
                'asal_barang' => $validated['asal_barang'],
                'nomor_surat' => $validated['nomor_surat'],
                'lampiran' => $lampiranPath,
                'total_harga' => $totalHarga,
            ]);

            // Delete existing items
            $barangMasuk->items()->delete();

            // Create new items
            foreach ($validated['items'] as $item) {
                BarangMasukItem::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'nama_barang' => $item['nama_barang'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                    'total' => $item['harga'] * $item['jumlah'],
                    'tgl_expired' => $item['tgl_expired'],
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Barang masuk berhasil diupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        // Check authorization
        if (auth()->user()->role === 'operator' && $barangMasuk->operator_id !== auth()->id()) {
            abort(403);
        }

        if ($barangMasuk->lampiran) {
            Storage::disk('public')->delete($barangMasuk->lampiran);
        }

        $barangMasuk->delete();
        return response()->json(['success' => true, 'message' => 'Barang masuk berhasil dihapus']);
    }

    /**
     * Toggle verification status
     */
    public function toggleVerification(BarangMasuk $barangMasuk)
    {
        $barangMasuk->update(['is_verified' => !$barangMasuk->is_verified]);

        $status = $barangMasuk->is_verified ? 'terverifikasi' : 'belum terverifikasi';
        return response()->json(['success' => true, 'message' => "Status berhasil diubah menjadi {$status}"]);
    }

    /**
     * Get sub kategoris by kategori
     */
    public function getSubKategoris(Request $request)
    {
        $subKategoris = SubKategori::where('kategori_id', $request->kategori_id)->get();
        return response()->json($subKategoris);
    }

    /**
     * Get batas harga by sub kategori
     */
    public function getBatasHarga(Request $request)
    {
        $subKategori = SubKategori::find($request->sub_kategori_id);
        return response()->json(['batas_harga' => $subKategori->batas_harga]);
    }

    /**
     * Export to Excel
     */
    public function export(Request $request)
    {
        return Excel::download(new BarangMasukExport($request->all()), 'barang-masuk.xlsx');
    }

    /**
     * Print surat masuk
     */
    public function print(BarangMasuk $barangMasuk)
    {
        // Check authorization
        if (auth()->user()->role === 'operator' && $barangMasuk->operator_id !== auth()->id()) {
            abort(403);
        }

        $barangMasuk->load(['operator', 'subKategori.kategori', 'items']);

        $pdf = Pdf::loadView('pages.print-barang-masuk', compact('barangMasuk'));
        return $pdf->download('surat-masuk-' . $barangMasuk->id . '.pdf');
    }
}
