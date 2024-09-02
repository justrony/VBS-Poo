<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordCodeRequest;
use App\Http\Requests\ResetPasswordValidateCodeRequest;
use App\Mail\SendEmailForgotPasswordCode;
use App\Models\User;
use App\Service\ResetPasswordValidateCodeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RecoverPasswordCodeController extends Controller
{
    public function forgotPasswordCode(ForgotPasswordRequest $request): JsonResponse
    {

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Log::warning('E-mail não cadastrado.',
                ['email' => $request->email]);

            return response()->json([
                'status' => false,
                'message' => 'E-mail não encontrado!'
            ], 400);
        }
//AQUI
        try {
            $userPasswordReset = DB::table('password_reset_tokens')->where([
                ['email' => $request->email]
            ]);

            if (!$userPasswordReset) {
                $userPasswordReset->delete();
            }

            $code = mt_rand(100000, 999999);

            $token = Hash::make($code);

            $userNewPasswordResets = DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

            if ($userNewPasswordResets) {

                $currentDate = Carbon::now();

                $oneHourLater = $currentDate->addHour();

                $formattedTime = $oneHourLater->format('H:i');
                $formattedDate = $oneHourLater->format('d/m/y');

                Mail::to($user->email)->send(new SendEmailForgotPasswordCode
                ($user, $code, $formattedDate, $formattedTime));
            }

            Log::info('Recuperar senha.', ['email' => $request->email]);

            return response()->json([
                'status' => true,
                'message' => 'E-mail de recuperação de senha foi enviado com sucesso!',
            ], 200);
        } catch (Exception $e) {

            Log::warning('Erro ao recuperar senha.', ['email' => $request->email,
                'error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Erro ao recuperar senha.',
            ], 400);
        }
    }

    public function resetPasswordValidateCode(ResetPasswordValidateCodeRequest $request,
                                              ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse
    {
        try {

            $validationResult = $resetPasswordValidateCode->ResetPasswordValidateCode($request->email, $request->code);

            if (!$validationResult['status']) {

                return response()->json([
                    'status' => false,
                    'message' => $validationResult['message'],
                ], 400);

            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {

                Log::notice('Usuário não encontrado.', ['email' => $request->email]);

                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado!',
                ], 400);

            }

            Log::info('Código recuperar senha válido.', ['email' => $request->email]);

            return response()->json([
                'status' => true,
                'message' => 'Código recuperar senha válido!',
            ], 200);

        } catch (Exception $e) {

            Log::warning('Erro validar código recuperar senha.', ['email' => $request->email, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Código inválido!',
            ], 400);
        }
    }

    public function resetPasswordCode(ResetPasswordCodeRequest $request, ResetPasswordValidateCodeService $resetPasswordValidateCode): JsonResponse
    {
        //AQUI
        try {

            $validationResult = $resetPasswordValidateCode->resetPasswordValidateCode($request->email, $request->code);

            if (!$validationResult['status']) {

                return response()->json([
                    'status' => false,
                    'message' => $validationResult['message'],
                ], 400);

            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {

                Log::notice('Usuário não encontrado.', ['email' => $request->email]);

                return response()->json([
                    'status' => false,
                    'message' => 'Usuário não encontrado!',
                ], 400);

            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $token = $user->first()->createToken('api-token')->plainTextToken;

            $userPasswordResets = DB::table('password_reset_tokens')->where('email', $request->email);

            if ($userPasswordResets) {
                $userPasswordResets->delete();
            }

            Log::info('Senha atualizada com sucesso.', ['email' => $request->email]);

            return response()->json([
                'status' => true,
                'user' => $user,
                'token' => $token,
                'message' => 'Senha atualizada com sucesso!',
            ], 200);
        } catch (Exception $e) {

            Log::warning('Senha não atualizada.', ['email' => $request->email, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Senha não atualizada!',
            ], 400);

        }
    }
}



