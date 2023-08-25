<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{



    //Create the user
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['min:8', 'confirmed'],
            'ph_no'=>'required',
        ]);

        $user = User::create($validateData);

        if ($user) {
            $token = $user->createToken('auth_token')->accessToken;
            return response()->json([
                'token' => $token,
                'user_id' => $user->id, // Include the user's ID in the response
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

//LOG in  for user 

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
                'user_id' => $user->id, // Include the user's ID in the response
                'message' => 'Logged in as admin',
                'status' => 200
            ]);
        }

        return response()->json([
            'token' => $token,
            'user_id' => $user->id, // Include the user's ID in the response
            'message' => 'User authenticated successfully',
            'status' => 200
        ]);
    }




//Get specifuc user 

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


//Get all user 
    public function getAllUsers()
    {
        $users = User::all();

        return response()->json([
            'users' => $users,
            'message' => 'All users retrieved successfully',
            'status' => 200
        ]);
    }
}
