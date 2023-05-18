<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Authorize extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|255',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The Provide Credentials Are Incorrect'],
            ]);

        }

        return $user->createToken('user-login')->plainTextToken;

    }
}
