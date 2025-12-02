<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account found with this email'
                ]);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password'
                ]);
            }

            // Store user in session
            session()->put('user_id', $user->id);
            session()->put('user_name', $user->name);
            session()->put('user_email', $user->email);
            session()->save(); // Force save
            
            // Also set in cookie as backup with explicit path
            cookie()->queue('user_id', $user->id, 120, '/dashboard/Achiever/public', 'localhost', false, true);

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function signup(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! You can now login.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Signup failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        session()->flush();
        session()->invalidate();
        session()->regenerateToken();
        cookie()->queue(cookie()->forget('user_id'));
        
        return redirect('/profile')->with('message', 'Logged out successfully');
    }

    public function checkAuth(Request $request)
    {
        if (session()->has('user_id')) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => session('user_id'),
                    'name' => session('user_name'),
                    'email' => session('user_email')
                ]
            ]);
        }

        return response()->json([
            'authenticated' => false
        ]);
    }
}
