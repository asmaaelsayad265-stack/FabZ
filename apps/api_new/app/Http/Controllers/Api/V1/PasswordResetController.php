<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function forgot(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(['email' => $request->string('email')]);

        return response()->json([
            'message' => $status === Password::RESET_LINK_SENT
                ? 'Reset link sent.'
                : 'If your email exists, a reset link has been sent.',
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return response()->json([
            'message' => $status === Password::PASSWORD_RESET
                ? 'Password reset successfully.'
                : 'Unable to reset password.',
        ], $status === Password::PASSWORD_RESET ? 200 : 422);
    }
}

