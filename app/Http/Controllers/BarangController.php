<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::latest()->get();
        return view ('barangs.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barangs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'nama' => 'required',
        'harga' => 'required|numeric',
        'file' => 'nullable|file|max:2048',
    ]);

    $data = $request->only(['nama', 'deskripsi', 'harga']);

    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('barangs', 'public');
        $data['file'] = $path;
    }

    Barang::create($data);

    return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barangs.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request = validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'file' => 'nullable|file|max:2048',
        ]);
        $barang = Barang::findOrFail($id);
        $data = $request->only(['nama', 'deskripsi', 'harga']);


        if ($request->hasFile('file')) {
            if ($barang->file){
                \storage::disk('public'->delete($barang->file));
            }
            $data['file'] = $request->file('file')->store('barangs', 'public');
        }
        @barang->update($data);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->file) {
            \Storage::disk('public')->delete($barang->file);
        }
        $barang->delete();

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus');
    }

    public function Download(string $id)
    {
         $barang = Barang::findOrFail($id);

        if ($barang->file) {
            abort(404, 'File tidak ditemukan');
        }
        $path = \storage_path('app/public/'. $barang->file);

        if(!file_exists($path)){
            abort(400, 'File tidak ditemukan diserver');
        }
        return response()->download($path);
        
    }
    public function export()
    {
        $barangs = Barang::all();

        $filename = 'data-barang-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($barangs) {
            $file = fopen('php://output', 'w');

            // Baris header kolom
            fputcsv($file, ['ID', 'Nama', 'Deskripsi', 'Harga', 'Nama File']);

            // Baris data
            foreach ($barangs as $barang) {
                fputcsv($file, [
                    $barang->id,
                    $barang->nama,
                    $barang->deskripsi,
                    $barang->harga,
                    $barang->file ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
