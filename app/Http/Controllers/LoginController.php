<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    public function login(Request $request){
        $cpf = preg_replace('/\D/', '', $request->cpf);

        if(Auth::attempt(['cpf' =>$cpf, 'password' => $request->password])){
            $user = Auth::user();

            $token =  $request->user()->createToken('api-token', [], now()->addHours(1))->plainTextToken;
            

            return response()->json([

                'status' => true,
                'message' => 'Bem vindo!',
                'token' => $token,
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
