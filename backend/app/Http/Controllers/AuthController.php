<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        if (Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'message' => 'Login exitoso',
                'user' => $user,
                'token' => $token
            ], 200);
        }

        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout exitoso'], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'string|min:3|max:255|unique:users,username,' . $user->id,
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'required_with:password|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updateData = [];
        
        if ($request->filled('username')) {
            $updateData['username'] = $request->username;
        }
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'user' => $user->fresh()
        ], 200);
    }

    public function checkUsernameUnique(Request $request)
    {
        $username = $request->get('username');
        $exists = User::where('username', $username)->exists();
        
        return response()->json(['available' => !$exists]);
    }
}