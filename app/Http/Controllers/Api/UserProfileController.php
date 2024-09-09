<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class UserProfileController extends Controller
{

    public function updateProfile(UserProfileRequest $request): JsonResponse
    {
        $user = Auth::user();


        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Seu e-mail deve ser verificado antes de atualizar o perfil.',
                'data' => false,
            ], 400);
        }


        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'message' => 'Senha atual incorreta.',
                'data' => false,
            ], 400);
        }


        DB::beginTransaction();

        try {
            $user->update([
                'name' => $request->input('name'),
                'password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->password,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Perfil atualizado com sucesso!',
                'data' => true,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar perfil: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro ao atualizar perfil.',
                'data' => false,
            ], 500);
        }
    }


    public function destroyProfile(): JsonResponse
    {
        $user = Auth::user();

        try {
            $user->delete();

            return response()->json([
                'message' => 'Conta excluída com sucesso!',
                'data' => true,
            ], 200);
        } catch (Exception $e) {
            Log::error('Erro ao excluir conta: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro ao excluir conta.',
                'data' => false,
            ], 500);
        }
    }
}
