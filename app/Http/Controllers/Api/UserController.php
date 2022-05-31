<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showUsers()
    {
        $users = User::all();

        return response()->json($users, '201');
    }


    public function saveUser(Request $request)
    {
        $user = new User();

        $verifyUser = User::where('email', $request->email)->first();


        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (empty($verifyUser)) {
                $user->email = $request->email;
            } else {
                return response()->json('E-mail de usuário já cadastrado em outro usuário!', '400');
            }
        } else {
            return response()->json('Endereço de e-mail inválido!', '400');
        }

        if ($request->password === $request->confirm_password) {
            $user->password = Hash::make($request->password);
        } else {
            return response()->json('Senhas diferentes!', '400');
        }

        $user->name = $request->name;

        $user->save();

        return response()->json('Usuário cadastrado com sucesso', '201');
    }
}
