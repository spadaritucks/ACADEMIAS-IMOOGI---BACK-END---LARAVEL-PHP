<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    public function login(Request $request){

        if(Auth::attempt(['cpf' =>$request->cpf, 'password' => $request->password])){
            $user = Auth::user();

            return response()->json([

                'status' => true,
                'message' => 'Logado!',
                'user' => $user
            ],201);
        }else{
            return response()->json([

                'status' => false,
                'message' => 'CPF ou senha incorreta!',
            ],404);
        }


    }
}
