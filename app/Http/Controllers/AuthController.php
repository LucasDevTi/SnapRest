<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Return_;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('layouts.login');
    }

    public function login(LoginRequest $request) {

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials, $request->boolean('remember'))){
            $request->session()->regenerate();
            return redirect()->intended('gestao')->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas e stão incorretas.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Você foi desconectado com sucesso!');
    }

}
