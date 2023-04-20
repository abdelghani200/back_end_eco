<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;


class UserController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api')->except('getUserDetail');
    // }

    // }

    public function loginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Retrieve the user by email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json([
                'error' => 'Invalid email or password'
            ], 401);
        }

        // Check if the password matches
        if (Hash::check($credentials['password'], $user->password)) {

            // Generate a token for the user
            $token = $user->createToken('example')->accessToken->token;

            // Set the redirect URL based on the user's role
            $redirectUrl = ($user->role === 'admin') ? '/dashboard' : '/Cart';

            // Add additional data to the response
            $data = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ];

            return response()->json([
                'token' => $token,
                'redirect_url' => $redirectUrl,
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'error' => 'Invalid email or password'
            ], 401);
        }
    }



    /**
     * register user.
     */

    public function registerUser(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'lastName' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'ville' => 'required|max:255',
            'address' => 'required|max:255',
            'numTele' => 'required|max:255',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'lastName' => $validatedData['lastName'],
            'email' => $validatedData['email'],
            'ville' => $validatedData['ville'],
            'address' => $validatedData['address'],
            'numTele' => $validatedData['numTele'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // dd($user);

        $token = $user->createToken('exemple')->accessToken->token;
        // var_dump($token);

        return response()->json([
            'status' => 200,
            'token' => $token
        ]);
    }


    public function getUserDetail(Request $request)
    {
        $accessToken = PersonalAccessToken::where('token', $request->bearerToken())->first();

        // dd($accessToken);
        // dd(Auth::check());

        if ($accessToken) {
            // dd($accessToken->user_id);
            $user = User::find($accessToken->user_id);
            // dd($user);
            return response()->json(['user' => $user], 200);
        } else {
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }


    /**
     * Display the specified resource.
     */
    public function userLogout(Request $request)
    {
        $accessToken = PersonalAccessToken::where('token', $request->bearerToken())->first();

        // dd($accessToken);
        // dd($accessToken);    
        $user = User::find($accessToken->user_id);
        $user->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logout successfully'
        ]);
    }

    
}
