<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class AuthController extends Controller
{
    public function login(Request $request){
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',

        ]);
        $credentials = $request->only(['email', 'password']);

        if (auth()->attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        dd(  $credentials);

        return redirect()->back()->withErrors(['Invalid credentials']);
    
    }

    public function signUpPost(Request $request){
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            ]);
            $user = new User();
            $user->name = $request->input('username');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->save();
            return redirect()->route('login');
    }

    public function signUp(Request $request){
        return view('register');
    }


    public function dashboard(Request $request){
        return view('dashboard');
    }
}
