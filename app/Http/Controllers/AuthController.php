<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            return ApiResponse::success([
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 'Usuario registrado correctamente', 201);
            
        } catch (ValidationException $e) {
            return ApiResponse::error('Error de validación', 422, $e->errors());
        } catch (Exception $e) {
            return ApiResponse::error('Error interno al registrar usuario', 500);
        }
    }

    /**
     * Inicio de sesión
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($credentials)) {
                return ApiResponse::error('Credenciales incorrectas', 401);
            }

            $user = User::find(Auth::id());

            return ApiResponse::success([
                'user' => $user,
                'roles' => $user->roles->pluck('name'),
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 'Has iniciado sesión');

        } catch (ValidationException $e) {
            return ApiResponse::error('Error de validación', 422, $e->errors());
        } catch (Exception $e) {
            return ApiResponse::error('Error interno al procesar el login', 500);
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return ApiResponse::success([], 'Sesión cerrada correctamente');
        } catch (Exception $e) {
            return ApiResponse::error('Error al cerrar sesión', 500);
        }
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return ApiResponse::error('La contraseña actual no es correcta', 400);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return ApiResponse::success([], 'Contraseña actualizada correctamente');
        } catch (ValidationException $e) {
            return ApiResponse::error('Error de validación', 422, $e->errors());
        } catch (Exception $e) {
            return ApiResponse::error('Error interno al actualizar contraseña', 500);
        }
    }
}
