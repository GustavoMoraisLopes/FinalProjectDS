<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profiles.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Remover avatar se solicitado
        if ($request->has('remove_avatar')) {
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }
            $user->update(['avatar' => null]);
            return redirect()->route('profile.show')->with('success', 'Foto de perfil removida com sucesso!');
        }

        // Upload avatar se fornecido
        if ($request->hasFile('avatar')) {
            // Deletar avatar antigo se existir
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.show')->with('success', 'Palavra-passe alterada com sucesso!');
    }
}
