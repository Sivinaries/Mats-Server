<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->level = 'User';
        $user->save();  // Save the additional attributes

        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect('/')->with('toast_success', 'Registration successful!')
            ->with('access_token', $token);
    }

    public function signin(Request $request)
    {
        $qrToken = Str::random(40);

        $deviceId = Str::random(16); // Always generates a new 16 character string

        if ($request->has('qrToken')) {
            $name = $request->input('name'); // Default to 'Unknown User' if no name is provided

            $user = new User();
            $user->name = $name;
            $user->level = 'Pivot';
            $user->password = bcrypt('123456');
            $user->email = $deviceId . '@device.com'; // Generate email using the new deviceId
            $user->device_id = $deviceId; // Attach the new deviceId
            $user->qr_token = $qrToken; // Save the QR token
            $user->save();

            Auth::login($user);

            $token = $user->createToken('auth_token')->plainTextToken;

            if ($user->level === 'Admin') {
                return redirect()->route('dashboard')->with('auth_token', $token);
            }

            return redirect()->route('user-home')->with('auth_token', $token)->with('toast_success', 'Login successful!');;
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return redirect()->route('login')->withErrors(['email' => 'Unauthorized']);
        }

        // Handle regular login
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        if ($user->level === 'Admin') {
            return redirect()->route('dashboard')->with('auth_token', $token)->with('toast_success', 'Login successful!');;
        }

        return redirect()->route('user-home')->with('auth_token', $token)->with('toast_success', 'Login successful!');;
    }

    public function logout(Request $request)
    {
        if ($user = Auth::guard('web')->user()) {

            $user->tokens()->delete();
        }

        Auth::guard('web')->logout();
        return redirect()->route('login')->with('toast_success', 'Logged Out Successful!');;
    }
}
