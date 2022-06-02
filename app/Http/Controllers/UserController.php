<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request) //function login
    {
        $credentials = $request->only('email', 'password'); 
        //hal diatas sama saja dengan 'email' -> $request, 'password' -> $request

        try{
            if( ! $token = JWTAuth::attempt($credentials)) {//jwt auth akan mengquery/cek apakah ada atau tidak
                return response()->json(['message' => 'Invalid credentials'], 400);
            }
        }catch(JWTException $e) {
            return response()->json(['message' => 'Couldnt create token'], 500);
        }

        $data = User::where('email', '=', $request->email)-> get();
        return response()->json([
            'status' => 1,
            'message' => 'Succes login!',
            'token' => $token,
            'data' => $data
        ]);
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'type' => 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'type' => $request->get('type'),
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'),201);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        // return response()->json(compact('user'));

        return response()->json([
            'status' => 1,
            'message' => 'Succes login!',
            'data' => $user
        ]);
    }
}

