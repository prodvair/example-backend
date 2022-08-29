<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.api', ['except' => ['login','register']]);
    }

    /**
     * Function for Login user from JWT method on fron-end
     *
     * @return json
     */
    public function login(LoginRequest $request)
    {
        // Checking request method
        if (! $request->isMethod('post')) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Bad method',
            ], 405);
        }

        // Checking user for access
        $credentials = $request->only('email', 'password');

        // Trying create JWT token for user
        $token = Auth::attempt($credentials);
        if (!$token) {
            // If failed then return message about it
            return response()->json([
                'status'    => 'error',
                'message'   => 'Unauthorized',
            ], 401);
        }

        // Else auth user in system and return he's token
        $user = Auth::user();
        return response()->json([
                'status'        => 'success',
                'user'          => $user,
                'authorization' => [
                    'token' => $token,
                    'type'  => 'bearer',
                ]
            ]);

    }


    /**
     * Function for registrathion user in system
     *
     * @return json
     */
    public function register(RegisterRequest $request){
        // Create a new user
        $user = User::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        // Auth in sistem and return user data
        $token = Auth::login($user);
        return response()->json([
            'status'        => 'success',
            'message'       => 'User created successfully',
            'user'          => $user,
            'authorization' => [
                'token' => $token,
                'type'  => 'bearer',
            ]
        ]);
    }

    /**
     * Function for logout user in system
     *
     * @return json
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status'    => 'success',
            'message'   => 'Successfully logged out',
        ]);
    }

    /**
     * Get user personal data
     *
     * @return json
     */
    public function me()
    {
        return response()->json([
            'status'    => 'success',
            'user'      => Auth::user(),
        ]);
    }

    /**
     * This function need when token is old dated, and return new JWT token
     *
     * @return json
     */
    public function refresh()
    {
        return response()->json([
            'status'        => 'success',
            'user'          => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type'  => 'bearer',
            ]
        ]);
    }
}
