<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MutasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return response()->json(Mutasi::all(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jenis_mutasi' => 'required|string|max:255',
            'jumlah' => 'required|integer',
            'id_barang' => 'required|exists:barang,id',
            'id_pengguna' => 'required|exists:users,id',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            dd(response()->json($validator->errors()));
            return response()->json($validator->errors(), 422);
        }

        $mutasi = Mutasi::create($request->all());

        return response()->json($mutasi, 201);
    }

    public function show(Mutasi $mutasi)
    {
        return response()->json($mutasi, 200);
    }

    public function update(Request $request, Mutasi $mutasi)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'sometimes|date',
            'jenis_mutasi' => 'sometimes|string|max:255',
            'jumlah' => 'sometimes|integer',
            'id_barang' => 'sometimes|exists:barang,id_barang',
            'id_pengguna' => 'sometimes|exists:users,id_pengguna',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mutasi->update($request->all());

        return response()->json($mutasi, 200);
    }

    public function destroy(Mutasi $mutasi)
    {
        $mutasi->delete();
        return response()->json(['message' => 'Mutasi deleted successfully'], 200);
    }
}
