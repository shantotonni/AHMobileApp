<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtauth:api', ['except' => ['login','dashboardLogin']]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'UserID' => 'required',
            'Password' => 'required',
        ]);

        if ($token = JWTAuth::attempt(['UserID' => $request->UserID, 'password' => $request->Password,'is_active' =>1])){
             $user = Auth::user();
             $update_user = User::where('id',$user->id)->first();
             $update_user->device_token = $request->device_token;
             $update_user->save();
            return response()->json([
                'status'=>200,
                'token'=>$token,
                'user' => $update_user
            ],200);
        }
        return response()->json([
            'message'=>'UserId or Password Not Match',
            'status'=>401
        ],200);


    }

    public function dashboardLogin(Request $request)
    {
        $this->validate($request, [
            'UserID' => 'required',
            'Password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt(['UserID' => $request->UserID, 'password' => $request->Password,'Active' =>'Y','RoleId'=>[1,2]])){
            return response()->json([
                'message'=>'UserId or Password Not Match',
                'status'=>401
            ],200);
        }

        return response()->json([
            'status'=>200,
            'token'=>$token,
            'user' => Auth::user()
        ],200);
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {

            $user = Auth::user();
            $update_user = User::where('id',$user->id)->first();
            $update_user->device_token = '';
            $update_user->save();

            $this->guard()->logout();

        } catch (\Exception $exception) {

        }
        return response()->json([
            'status'=>200,
            'message' => 'Successfully logged out'
        ],200);

    }

}
