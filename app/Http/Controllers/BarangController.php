<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return response()->json(Barang::all(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:barang',
            'kategori' => 'required|string|max:255',
            'lokasi' => 'nullable|string',
            'stok_awal' => 'nullable|integer',
            'harga' => 'nullable|numeric',
            'pemasok' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang = Barang::create($request->all());

        return response()->json($barang, 201);
    }

    public function show(Barang $barang)
    {
        return response()->json($barang, 200);
    }

    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'sometimes|string|max:255',
            'kode' => 'sometimes|string|max:255|unique:barang,kode,' . $barang->id,
            'kategori' => 'sometimes|string|max:255',
            'lokasi' => 'nullable|string',
            'stok_awal' => 'nullable|integer',
            'harga' => 'nullable|numeric',
            'pemasok' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang->update($request->all());

        return response()->json($barang, 200);
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return response()->json(['message' => 'Barang deleted successfully'], 200);
    }

    public function historyMutasi(Barang $barang)
    {
        $mutasi = $barang->mutasi;
        return response()->json($mutasi, 200);
    }
}
