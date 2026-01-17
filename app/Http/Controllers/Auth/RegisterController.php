<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validação customizada com regras de instituição
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'institution' => ['required', 'string', 'in:istec,ipta'],
        ]);

        // Regra de negócio: @my.istec.pt só pode ser ISTEC
        if (str_ends_with($validated['email'], '@my.istec.pt') && $validated['institution'] !== 'istec') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Emails @my.istec.pt só podem ser registados como ISTEC.']);
        }

        // Regra de negócio: outras instituições não podem ser IPTA se não forem emails IPTA
        if ($validated['institution'] === 'ipta' && !str_ends_with($validated['email'], '@ipta.pt')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Para registar como IPTA, utilize um email @ipta.pt']);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'user_type' => 'student',
            'institution' => $validated['institution'],
        ]);

        Auth::login($user);

        return redirect('dashboard');
    }
}
