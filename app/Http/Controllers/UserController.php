<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 


class UserController extends Controller
{

    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['min:8', 'confirmed']
        ]);

        $user = User::create($validateData);

        if ($user) {
            $token = $user->createToken('auth_token')->accessToken;
            return response()->json([
                'token' => $token,
                'user' => $user,
                'message' => 'User created successfully',
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to create user',
                'status' => 500
            ], 500);
        }
    }


    public function login(Request $request)
{
    $validateData = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required']
    ]);

    $user = User::where('email', $validateData['email'])->first();

    if (!$user || !Hash::check($validateData['password'], $user->password)) {
        return response()->json([
            'message' => 'Invalid email or password',
            'status' => 401
        ], 401);
    }

    $token = $user->createToken('auth_token')->accessToken;

    if ($validateData['email'] === 'saswatranjan0602@gmail.com' && $validateData['password'] === 'Saswat@0602') {
        return response()->json([
            'token' => $token,
            'user' => $user,
            'message' => 'Logged in as admin',
            'status' => 200
        ]);
    }

    return response()->json([
        'token' => $token,
        'user' => $user,
        'message' => 'User authenticated successfully',
        'status' => 200
    ]);
}


    public function getUser($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                'user' => $user,
                'message' => 'User  not found ',
                'status' => 400
            ]);
        } else {
            return response()->json([
                'user' => $user,
                'message' => 'User  found ',
                'status' => 200
            ]);
        }
    }
}
