<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Traits\Loggable;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    use Loggable;
    /**
     * Registro de usuario
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'user_name' => 'required',
                'password' => 'required'
            ]);


            $user = User::create([
                'user_name' => $request->name,
                'password' => Hash::make($request->password),
            ]);

            return ApiResponse::success([
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 'Usuario registrado correctamente', 201);
            
        } catch (ValidationException $e) {
            return ApiResponse::error('Error de validación', 422, $e->errors());
        } catch (Exception $e) {
            $this->logError($e);
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
                'user_name' => 'required',
                'password' => 'required'
            ]);

            if (!Auth::attempt(['user_name' => $credentials['user_name'], 'password' => $credentials['password']])) {
                return ApiResponse::error('Credenciales incorrectas', 401);
            }
    
            $user = User::with('employee','orders:id,folio,user_id,table_id', 'orders.table:id,name')->find(Auth::id());

            $user->orders->makeHidden($user->orders->first()?->getAppends());

            return ApiResponse::success([
                'user' => $user,
                'roles' => $user->roles->pluck('name'),
                'orders' => $user->roles->pluck('name'),
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 'Has iniciado sesión');

        } catch (ValidationException $e) {
            return ApiResponse::error('Error de validación', 422, $e->errors());
        } catch (Exception $e) {
            $this->logError($e);
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
            $this->logError($e);
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
                'currentPassword' => 'required',
                'newPassword' => 'required',
                'newPassword_confirmation' => 'required',
            ]);

            $user = $request->user();

            if (!Hash::check($request->currentPassword, $user->password)) {
                return ApiResponse::error('La contraseña actual no es correcta', 400);
            }

            if ($request->newPassword !== $request->newPassword_confirmation) {
                return ApiResponse::error('Las contraseñas no coinciden', 400);
            }

            $user->password = Hash::make($request->newPassword);
            $user->save();

            return ApiResponse::success([], 'Contraseña actualizada correctamente');
        } catch (ValidationException $e) {
            return ApiResponse::error('Error de validación', 422, $e->errors());
        } catch (Exception $e) {
            $this->logError($e);
            return ApiResponse::error('Error interno al actualizar contraseña', 500);
        }
    }
}
