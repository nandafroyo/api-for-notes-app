<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['signup', 'signin']]);
    }

    public function signup(Request $request) {
        $this->validate($request, [
            'fullname'  => 'required',
            'username'  => 'required|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ]);

        $createUser = User::create([
            'fullname' => $request->json('fullname'),
            'username' => $request->json('username'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password')),
        ]);

        return $createUser;
    }

    public function signin(Request $request)
    {
        $data = $request->all();
        //can validate using email or username
        $loginType = filter_var( $data['username'], FILTER_VALIDATE_EMAIL ) ? 'email' : 'username';

        if (! $token = auth()->attempt([$loginType => $data['username'], 'password' => $data['password']])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user_id' => $request->user()->id,
            'username' => $request->user()->username,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function signout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
