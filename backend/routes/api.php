<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'CORS funciona correctamente!']);
});

// Minimal test - just return request data
Route::post('/login-simple', function (Request $request) {
    return response()->json([
        'message' => 'Endpoint reached successfully',
        'received_data' => $request->all(),
        'method' => $request->method(),
        'headers' => $request->headers->all()
    ], 200);
});

// Test database connection
Route::get('/test-db', function () {
    try {
        $count = \App\Models\User::count();
        return response()->json([
            'message' => 'Database connection OK',
            'user_count' => $count
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Simple auth without tokens
Route::post('/register-simple', function (Request $request) {
    try {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = \App\Models\User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user
        ], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::post('/login-auth', function (Request $request) {
    try {
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        if (\Illuminate\Support\Facades\Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
            $user = \Illuminate\Support\Facades\Auth::user();
            return response()->json([
                'message' => 'Login exitoso',
                'user' => $user
            ], 200);
        }
        
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Auth routes - Public (with tokens)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/check-username', [AuthController::class, 'checkUsernameUnique']);

// Simple profile update without tokens
Route::put('/profile-simple/{id}', function (Request $request, $id) {
    try {
        $user = \App\Models\User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'user' => $user->fresh()
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Auth routes - Protected (with tokens)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});