<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\OrangTua;
use App\Models\Staf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // Check credentials
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('username', $request->username)->with('role')->firstOrFail();

        // Update last login time
        $user->update([
            'last_login_at' => now(),
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => $user->username
        ]);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Get user profile based on role
        $profile = null;
        if ($user->role) {
            switch (strtolower($user->role->role)) {
                case 'guru':
                    $profile = Guru::where('id_user', $user->id_user)->first();
                    break;
                case 'orangtua':
                    $profile = OrangTua::where('id_user', $user->id_user)->first();
                    break;
                case 'staf':
                    $profile = Staf::where('id_user', $user->id_user)->first();
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'role' => $user->role->role,  // Menambahkan role ke dalam response
            ],
            'profile' => $profile
    ]);
    }
    
    public function saveFcmToken(Request $request)
    {
        // Validasi token dan id_user
        $request->validate([
            'fcm_token' => 'required|string',
            'id_user' => 'required|integer', // Validasi id_user
        ]);

        // Ambil user berdasarkan id_user yang dikirim dalam body request
        $user = User::find($request->id_user);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Simpan FCM token
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json(['message' => 'FCM Token saved successfully!'], 200);
    }



    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('role');

        // Get user profile based on role
        $profile = null;
        if ($user->role) {
            switch (strtolower($user->role->role)) {
                case 'guru':
                    $profile = Guru::where('id_user', $user->id_user)->first();
                    break;
                case 'orangtua':
                    $profile = OrangTua::where('id_user', $user->id_user)->first();
                    break;
                case 'staf':
                    $profile = Staf::where('id_user', $user->id_user)->first();
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'profile' => $profile
            ]
        ]);
    }
}
