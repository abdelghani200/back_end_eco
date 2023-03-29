<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request)
    {
        $input = $request->all();

        Auth::attempt($input);

        // $user = Auth::user();
        $user = User::find(1);
        // dd($user);

        $token = $user->createToken('exemple')->accessToken->token;

        return response()->json([
            'status' => 200,
            'token' => $token
        ]);
       
    }


    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail()
    {
        
        $user = User::find(1);
        
        return response()->json(['data' => $user], 200);

    }


    /**
     * Display the specified resource.
     */
    public function userLogout()
    {
        $user = User::find(1);
        $user->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logout successfully'
        ]);
    }
}
