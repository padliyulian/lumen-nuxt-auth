<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function guard() {
        return Auth::guard('api');
    }

    public function user()
    {
        return Auth::user();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|max:255|confirmed',
        ]);

        if($validator->fails()){
            return response([
                'message' => 'Validation errors',
                'errors' =>  $validator->errors(),
                'status' => false
            ], 422);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
      
        $data['token'] =  $user->createToken('MyApp')->accessToken;
        $data['name'] =  $user->name;

        return response()->json([
            'message' => 'Account Created Success',
            'status' => true,
            'data' => $data
        ]);
    }  

    public function login(Request $request)
    {
        $valid_data = $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        if (Hash::check($request->password, $user->password)) {
            // login log record
            $user->isLogin = "1";
            $user->last_login_at = Carbon::now()->toDateTimeString();
            $user->last_login_ip = $request->ip();
            $user->last_login_agent = $request->userAgent();
            $user->update();

            $data['user'] = $user;
            $data['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json([
                'message' => 'Login Success',
                'status' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'message' => 'Login Failed',
                'status' => false,
                'data' => ''
            ], 422);
        }
        // if(Auth::attempt($valid_data)) {
        //     return response(['message' => 'Invalid  credentials']);
        // }

        // $user = User::where('email', $request->email)->first();
        // $data['user'] = $user;
        // $data['token'] =  $user->createToken('MyApp')->accessToken;
        // return response()->json([
        //     'message' => 'Login Success',
        //     'status' => true,
        //     'data' => $data
        // ]);
    }

    public function logout() {
        if (Auth::check()) {
            Auth::user()->token()->delete();
            $this->user()->isLogin = "0";
            $this->user()->update();
            return response()->json(['message' => 'User logout']);
        }
    }
}
