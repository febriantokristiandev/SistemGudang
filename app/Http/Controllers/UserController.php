<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login', 'register']);
    }

    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'kata_sandi' => 'required|string|min:8',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'tanggal_bergabung' => 'required|date',
            'peran' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'peran' => $request->peran,
        ]);
    
        // Generate token
        $token = $user->createToken('Personal Access Token')->plainTextToken;
    
        // Return user data with token
        return response()->json([
            'id' => $user->id,
            'nama' => $user->nama,
            'email' => $user->email,
            'alamat' => $user->alamat,
            'nomor_telepon' => $user->nomor_telepon,
            'tanggal_bergabung' => $user->tanggal_bergabung,
            'peran' => $user->peran,
            'token' => $token,
        ], 201);
    }
    
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'kata_sandi' => 'sometimes|string|min:8',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'tanggal_bergabung' => 'sometimes|date',
            'peran' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->has('kata_sandi')) {
            $request->merge(['kata_sandi' => Hash::make($request->kata_sandi)]);
        }

        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function historyMutasi(User $user)
    {
        $mutasi = $user->mutasi; 
        return response()->json($mutasi, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'kata_sandi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->kata_sandi, $user->kata_sandi)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'kata_sandi' => 'required|string|min:8',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'tanggal_bergabung' => 'required|date',
            'peran' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'peran' => $request->peran,
        ]);

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }
}
