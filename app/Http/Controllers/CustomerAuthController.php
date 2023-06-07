<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerAuthController extends Controller
{
    function __construct() {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'Mobile' => 'required',
            'Password' => 'required',
        ]);

        if ($token = JWTAuth::attempt(['Mobile' => $request->Mobile, 'password' => $request->Password,'Status'=>'Y'])){
            $user = Auth::user();
            return response()->json([
                'status'=>200,
                'token'=>$token,
                'user'=>$user,
            ],200);
        }
        return response()->json([
            'message'=>'UserId or Password Not Match',
            'status'=>401
        ],200);
    }

    public function sendOtp(Request $request){
        $this->validate($request, [
            'Mobile' => 'required|min:11|max:11',
        ]);

        $exist_customer = Customer::where('Mobile',$request->Mobile)->exists();
        if ($exist_customer){
            return response()->json([
                'status'=>200,
                'message' => 'Mobile number Already Exists'
            ],200);
        }else{
            try {
                $six_digit_random_number = random_int(100000, 999999);
                $smscontent = 'Otp Code - '.$six_digit_random_number;
                $mobileno = $request->Mobile;
                $this->sendsms($ip = '192.168.100.213', $userid = 'motors', $password = 'Asdf1234', $smstext = urlencode($smscontent), $receipient = urlencode($mobileno));

                $otp = new Otp();
                $otp->OtpCode = $six_digit_random_number;
                $otp->Mobile = $mobileno;
                $otp->Status = 0;
                $otp->save();

                return response()->json([
                    'status'=>200,
                    'message'=>'success'
                ]);
            }catch (\Exception $e){
                return response()->json([
                    'status'=>401,
                    'error'=>$e
                ]);
            }
        }
    }

    public function checkOtp(Request $request){
        $otp = $request->OtpCode;
        $mobileno = $request->Mobile;

        $check_otp = Otp::where('Mobile',$mobileno)->where('OtpCode',$otp)->where('Status',0)->orderBy('CreatedDate','desc')->first();
        if ($check_otp){
            $check_otp->Status = 1;
            $check_otp->save();
            return response()->json([
                'status'=>200,
                'message'=>'Otp Match Successfully'
            ]);
        }else{
            return response()->json([
                'status'=>401,
                'message'=>'Otp Not Match'
            ]);
        }
    }

    public function registration(Request $request){
        $this->validate($request, [
            'Name' => 'required',
            'Mobile' => 'required',
            'Password' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $exist_customer = Customer::where('Mobile',$request->Mobile)->exists();
            if ($exist_customer){
                return response()->json([
                    'status'=>401,
                    'message' => 'Mobile number Already Exists'
                ],200);
            }

            $customer = new Customer();
            $customer->Name = $request->Name;
            $customer->Mobile = $request->Mobile;
            $customer->password = bcrypt($request->Password);
            $customer->Status = 'Y';
            if ($customer->save()){
                if ($token = JWTAuth::attempt(['Mobile' => $request->Mobile, 'password' => $request->Password])){
                    $user = Auth::user();
                    DB::commit();
                    return response()->json([
                        'status'=>200,
                        'token'=>$token,
                        'user'=>$user,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>200,
                        'message'=>'',
                    ],200);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'=>400,
                'message'=>$e->getMessage()
            ],400);
        }

    }

    public function updateProfile(Request $request){
        $this->validate($request, [
            'name' => 'required',
//            'Division' => 'required',
//            'District' => 'required',
//            'Upazilla' => 'required',
        ]);

        $customer = Customer::where('id',$request->id)->first();
        $customer->name = $request->name;
        $customer->Mobile = $request->Mobile;
        $customer->Address = $request->Address;
        $customer->save();

        return response()->json([
            'status'=>200,
            'message'=>'success'
        ],200);
    }

    public function changePassword(Request $request){
        $this->validate($request,[
            'previous_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $current_password = Auth::User()->password;
        $customer = JWTAuth::parseToken()->authenticate();

        if(Hash::check($request->previous_password, $current_password))
        {
            if(Hash::check($request->password, $current_password)){
                return response()->json(['message'=>'Previous Password and Old Password Same']);
            }else{
                $customer = Customer::where('ID',$customer->ID)->first();
                $customer->password = bcrypt($request->password);
                $customer->save();
                return response()->json(['message'=>'Password Change successfully :)']);
            }

        }else{
            return response()->json(['message'=>'Previous Password Not Correct :)']);
        }
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->guard()->logout();
        } catch (\Exception $exception) {

        }
        return response()->json([
            'status'=>200,
            'message' => 'Successfully logged out'
        ],200);
    }

    public function sendsms($ip, $userid, $password, $smstext, $receipient) {
        $smsUrl = "http://{$ip}/httpapi/sendsms?userId={$userid}&password={$password}&smsText=" . $smstext . "&commaSeperatedReceiverNumbers=" . $receipient;
        //echo $smsUrl; exit();
        $response = file_get_contents($smsUrl);
        //print_r($response); exit();
        return json_decode($response);
    }
}
