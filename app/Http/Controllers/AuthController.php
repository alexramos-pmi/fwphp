<?php

namespace App\Http\Controllers;

use Exception;
use App\Core\Auth;
use App\Http\Request;
use App\Foundation\Http;

class AuthController
{
    public function showLoginForm()
    {
        inertia('Login');
    }

    public function login(Request $request)
    {
        try
        {
            if(!Auth::attempt($request->input('email'), $request->input('password')))
            {
                throw new Exception('Credenciais inválidas.');
            }

            $user = [
                'userid' => Auth::user()->id,
                'username' => Auth::user()->name,
                'useremail' => Auth::user()->email,
                'userlevel' => Auth::user()->level,
                'userlevelname' => Auth::user()->level_name
            ];

            return response()->json(['success' => "Login efetuado com sucesso", 'user' => $user], Http::OK);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], Http::UNPROCESSABLE_ENTITY);
        }
    }

    public function checklogin()
    {
        return response()->json(['check' => Auth::check()], Http::OK);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['success' => $request->input('message')], Http::OK);
    }
}