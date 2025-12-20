<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgotPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // Criar token seguro usando o Password broker (grava em password_reset_tokens)
        if ($user) {
            $token = Password::createToken($user);
            $user->notify(new ForgotPasswordNotification($token));
        } else {
            // Não revelar se o email existe: envia mesmo assim (não grava token porque não há utilizador)
            $fakeToken = Password::getRepository()->createNewToken();
            Notification::route('mail', $request->email)
                ->notify(new ForgotPasswordNotification($fakeToken));
        }

        return response()->json([
            'success' => true,
            'message' => 'Se o email existir, receberá um link de recuperação. Verifique a sua caixa de entrada.'
        ]);
    }
}
