<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
    public function create()
    {
        return view('register.create');
    }

    public function show()
    {
        return view('sessions.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required|max:255',
            'username' => 'required|min:3|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $attributes['password'] = Hash::make($attributes['password']);

        $create = User::create($attributes);

        auth()->login($create);

        return redirect('/');
    }



    public function login(Request $request)
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($attributes)) {
            $request->session()->regenerate();
 
            return redirect()->intended('login');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Goodbye!');
    }

}
