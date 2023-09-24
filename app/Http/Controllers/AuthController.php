<?php

namespace App\Http\Controllers;

use DB;
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
        $fields = $request->validate([
            'email' => 'required|string',
            'old_password' => 'required|string',
            'new_password' => 'required|confirmed|string',
        ]);

        // get email
        $user = User::where('email', $fields['email'])->first();


        if(!Hash::check($fields['old_password'], $user->password)){
            return response([
                'message' => 'Old password doesnt match!'
            ], 401);
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        DB::table('change_logs')->insert(
            array(
                   'userId'     =>   $user->id, 
                   'changedItem'   =>   'password',
                   'oldValue'   =>   $fields['old_password'],
                   'newValue'   =>   $fields['new_password'],
            )
        );

        return response("Success", 201);
    }

    public function updateEmail(Request $request) {
       $fields =  $request->validate([
        // 'id'=>'string|required',
        'email'=>'email|string|required'
    ]);

        // get user
        $user = User::where('id', auth()->user()->id)->first();

        $oldEmail = $user->email;

        User::whereId(auth()->user()->id)->update([
            'email' => $fields['email']
        ]);

        DB::table('change_logs')->insert(
            array(
                   'userId'     =>   $user->id, 
                   'changedItem'   =>   'email',
                   'oldValue'   =>   $oldEmail,
                   'newValue'   =>   $fields['email'],
            )
        );

        return response("Success", 201);
    }

    public function updateName(Request $request) {
        $fields =  $request->validate([
                'name' =>'string|required'
            ]);

        // get user
        $user = User::where('id', auth()->user()->id)->first();

        $oldName = $user->name;
 
         User::whereId(auth()->user()->id)->update([
             'name' => $fields['name']
         ]);

         DB::table('change_logs')->insert(
            array(
                   'userId'     =>   $user->id, 
                   'changedItem'   =>   'name',
                   'oldValue'   =>   $oldName,
                   'newValue'   =>   $fields['name'],
            )
        );
 
         return response("Success", 201);
     }
}
