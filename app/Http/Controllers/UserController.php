<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function index()
    {
        $users = User::where('level', 'Cashier')->get();
        
        return view('user', compact('users'));
    }

    public function create()
    {

        return view('adduser');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
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

        $user->level = 'Cashier';
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect('/users')->with('toast_success', 'Registration successful!')
            ->with('access_token', $token);
    }


    public function destroy($id)
    {
        User::destroy($id);

        return redirect(route('user'))->with('success', 'User Berhasil Dihapus !');
    }
}
