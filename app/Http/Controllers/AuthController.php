<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Login Failed! Bad credentials'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function getCurrentUser(Request $request) {
        $fields = $request->validate([
            'currentToken' => 'required|string',
        ]);

        if(!\Laravel\Sanctum\PersonalAccessToken::findToken($fields['currentToken'])) {
            return response([
                'message' => 'Error! Access Token doesnt exist'
            ], 401);
        }

        // Fetch the associated token Model
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($fields['currentToken']);

        // Get the assigned user
        $user = $token->tokenable;

        return $user;
    
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|confirmed|string',
        ]);


        if(!Hash::check($request->old_password, auth()->user()->password)){
            return back()->with("error", "Old Password Doesn't match!");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response("success", 201);
    }

    public function updateData(Request $request) {
        $request->validate([
            'name' =>'string',
            'email'=>'required|email|string|max:255'
        ]);

        $user = auth()->user();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->save();

        return back()->with('message','Profile Updated');
    }
}
