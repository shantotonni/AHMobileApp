<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    public function customerCreateServiceRequest(Request $request){
        $this->validate($request,[
            'customer_mobile'=>'required',
            'problem_section_id'=>'required',
            'customer_id'=>'required',
            'generator_model'=>'required',
        ]);

        DB::beginTransaction();
        try {

            $user = JWTAuth::parseToken()->authenticate();
            $FourDigitRandomNumber = rand(1231,7879);

            $service_request  = new ServiceRequest();
            $service_request->problem_section_id     = $request->problem_section_id;
            $service_request->customer_id            = $request->customer_id;
            $service_request->location               = $request->location;
            $service_request->customer_name          = $request->customer_name;
            $service_request->customer_mobile        = $request->customer_mobile;
            $service_request->problem_summary        = $request->problem_summary;
            $service_request->generator_rating       = $request->generator_rating;
            $service_request->generator_model        = $request->generator_model;
            $service_request->service_created_date   = Carbon::now();
            $service_request->service_request_status = 'created';
            $service_request->service_request_no     = 'DEGS'.$FourDigitRandomNumber;

            if ($service_request->save()) {
                $data = [];
                $data['title'] = 'Service Ticket No-'.$service_request->service_request_no;
                $data['body'] = 'You have been assign to a service Ticket';
                $data['action'] = 'service';
                $engineer = User::where('role_id',2)->pluck('id');
                sendNotification($data,$engineer);
                DB::commit();
                return response()->json([
                    'status'=>200,
                    'message' => 'Service Request Successfully Inserted',
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'=>400,
                'message'=>$e->getMessage()
            ],400);
        }
    }

    public function customerServiceRequestList(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $service_request = ServiceRequest::with('serviceType','engineer','technician','problem_Section')
            ->where('customer_id',$user->id)
            ->latest()->get();
        return new ServiceRequestCollection($service_request);
    }
}
