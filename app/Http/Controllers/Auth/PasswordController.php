<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // After password has been changed in the profile
        // we invalidate the old session and generate a new token

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // After password has been reset we redirect user to login page

        return redirect()->route('login')->with('status', 'Password has been updated. Please log in with your new password.');
    }
}
