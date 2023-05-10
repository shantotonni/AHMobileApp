<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckInput\CheckInputCollection;
use App\Http\Resources\ServicePeriod\ServicePeriodCategoryCollection;
use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Http\Resources\ServiceRequest\ServiceRequestResource;
use App\Models\AdditionalGeneratorInfo;
use App\Models\CheckingInputName;
use App\Models\CheckingInputValue;
use App\Models\QuestionAnswer;
use App\Models\ServicePeriodCategory;
use App\Models\ServicePeriodCategoryDetails;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class ServiceRequestController extends Controller
{
    public function engineerServiceRequestList(): ServiceRequestCollection
    {
        //$user = JWTAuth::parseToken()->authenticate();
        $service_request = ServiceRequest::with('serviceType','engineer','technician','problem_Section')
            //->where('engineer_id',$user->id)
            ->latest()->paginate(15);
        return new ServiceRequestCollection($service_request);
    }

    public function technicianPendingServiceRequestList(): ServiceRequestCollection
    {
        $user = JWTAuth::parseToken()->authenticate();
        $service_request = ServiceRequest::with('serviceType','engineer','technician','problem_Section')
            ->where(function ($query){
                $query->where('service_request_status','created')->orWhere('service_request_status','started')->orWhere('service_request_status','ongoing');
            })->where('technician_id',$user->id)
            ->latest()->paginate(15);
        return new ServiceRequestCollection($service_request);
    }

    public function engineerServiceRequestCreate(\App\Http\Requests\ServiceRequest\ServiceRequest $request){

        DB::beginTransaction();
        try {

            $user = JWTAuth::parseToken()->authenticate();
            $FourDigitRandomNumber = rand(1231,7879);

            $service_request  = new ServiceRequest();
            $service_request->technician_id         = $request->technician_id;
            $service_request->problem_section_id    = $request->problem_section_id;
            $service_request->service_type_id       = $request->service_type_id;
            $service_request->generator_info        = $request->generator_info;
            $service_request->site_name             = $request->site_name;
            $service_request->location              = $request->location;
            $service_request->contact_person_name   = $request->contact_person_name;
            $service_request->contact_number        = $request->contact_number;
            $service_request->problem_summary       = $request->problem_summary;
            $service_request->information           = $request->information;
            $service_request->service_created_date  = Carbon::now();
            $service_request->service_request_status= 'created';
            $service_request->service_request_no    = 'DEGS'.$FourDigitRandomNumber;
            $service_request->engineer_id           = $user->id;

            if ($service_request->save()) {
                $data = [];
                $data['title'] = 'Service Ticket No-'.$service_request->service_request_no;
                $data['body'] = 'You have been assign to a service Ticket';
                $data['action'] = 'service';
                $technician = User::where('id',$request->technician_id)->pluck('id');
                sendNotification($data,$technician);
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

    public function technicianStartServiceRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $service_request = ServiceRequest::where('id',$request->service_request_id)->where('technician_id',$user->id)->first();
            $service_request->service_started_date = Carbon::now();
            $service_request->service_request_status = "started";
            if ($service_request->save()){
                $data = [];
                $data['title'] = 'Service Ticket No-'.$service_request->service_request_no;
                $data['body'] = 'TSA Started Service Ticket';
                $data['action'] = 'service';
                $engineer = User::where('role_id',2)->pluck('id');
                sendNotification($data,$engineer);

                DB::commit();
            }
            return response()->json([
                'status'=>200,
                'message'=>'Service Started Successfully'
            ],200);

        }catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'=>400,
                'message'=>$e->getMessage()
            ],400);
        }
    }

    public function technicianArrivedSiteForServiceRequest(Request $request){

        DB::beginTransaction();
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $service_request_id = $request->service_request_id;

            $service_request                         = ServiceRequest::where('id',$service_request_id)->where('technician_id',$user->id)->first();
            $service_request->generator_info_id      = $request->generator_info_id;
            $service_request->initial_remarks        = $request->initial_remarks;
            $service_request->initial_issues         = $request->initial_issues;
            $service_request->site_arrived_date      = Carbon::now();
            $service_request->service_request_status = "ongoing";
            if ($service_request->save()){
                $data = [];
                $data['title'] = 'Service Ticket No-'.$service_request->service_request_no;
                $data['body'] = 'TSA Arrived At Site';
                $data['action'] = 'service';
                $engineer = User::where('role_id',2)->pluck('id');
                sendNotification($data,$engineer);

                DB::commit();
            }

            $checking_input = CheckingInputName::with(['checkingValue' => function($query) use($service_request_id){
                $query->where('service_request_id',$service_request_id);
            }])->latest()->get();

            return response()->json([
                'status'=>200,
                'message'=>'Technician Arrived At site Successfully',
                'checking_input'=>new CheckInputCollection($checking_input)
            ],200);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'=>400,
                'message'=>$e->getMessage()
            ],400);
        }
    }

    public function technicianSubmitServiceRequest(Request $request){

        DB::beginTransaction();

        try {

            $user = JWTAuth::parseToken()->authenticate();
            $service_request                         = ServiceRequest::where('id',$request->service_request_id)->where('technician_id',$user->id)->first();
            $service_request->final_issues           = $request->final_issues;
            $service_request->final_remarks          = $request->final_remarks;
            $service_request->service_request_status = "completed";
            $service_request->service_completed_date = Carbon::now();

            if ($service_request->save()) {
                if ($request->hasFile('image')) {
                    foreach ($request->file('image') as $file) {

                        $name = time().'.' . uniqid().'.'.$file->getClientOriginalExtension();
                        Image::make($file)->resize(500,600)->save(public_path('service_image/').$name);

                        $image                      = new ServiceRequestImage();
                        $image->image               = $name;
                        $image->service_request_id  = $service_request->id;
                        $image->save();
                    }
                }

                $question_answer_array = json_decode($request->question_answer);
                if (!empty($question_answer_array)){
                    foreach ($question_answer_array as $value){
                        $question_answer = new QuestionAnswer();
                        $question_answer->service_request_id = $request->service_request_id;
                        $question_answer->question_id = $value->question_id;
                        $question_answer->rating = $value->rating;
                        $question_answer->save();
                    }
                }

                $data = [];
                $data['title'] = 'Service Ticket No-'.$service_request->service_request_no;
                $data['body'] = 'TSA Submitted Service Ticket';
                $data['action'] = 'service';
                $engineer = User::where('role_id',2)->pluck('id');
                sendNotification($data,$engineer);

                DB::commit();
                return response()->json(['message' => 'Service Request Submitted Successfully','status' => 200], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'=>400,
                'message'=>$e->getMessage()
            ],400);
        }
    }

    public function technicianAddAdditionalGeneratorInfoServiceRequest(Request $request){

        $update_additional_info = AdditionalGeneratorInfo::where('service_request_id',$request->service_request_id)->first();

        if (empty($update_additional_info)){
            $update_additional_info                           = new AdditionalGeneratorInfo();
        }

        $update_additional_info->service_request_id         = $request->service_request_id;
        $update_additional_info->ratings                    = $request->ratings;
        $update_additional_info->volts                      = $request->volts;
        $update_additional_info->total_running_hours        = $request->total_running_hours;
        $update_additional_info->frequency                  = $request->frequency;
        $update_additional_info->phase                      = $request->phase;
        $update_additional_info->battery_charging_voltage   = $request->battery_charging_voltage;
        $update_additional_info->engine_coolant_temp        = $request->engine_coolant_temp;
        $update_additional_info->engine_oil_pressure        = $request->engine_oil_pressure;
        $update_additional_info->save();

        return response()->json([
           'status'=>200,
           'message'=>'Additional Generator Info Added'
        ],200);
    }

    public function getCheckingInput(Request $request){
        $service_request_id = $request->service_request_id;

        $checking_input = CheckingInputName::with(['checkingValue' => function($query) use($service_request_id){
            $query->where('service_request_id',$service_request_id);
        }])->latest()->get();

        return new CheckInputCollection($checking_input);
    }

    public function getServiceRequestByServiceRequestId(Request $request){
        $service_request_id = $request->service_request_id;
        $service_request = ServiceRequest::where('id',$service_request_id)->with('serviceType','engineer','technician','additionalGeneratorInfo','problem_Section')->first();

        $checking_input = CheckingInputName::with(['checkingValue' => function($quer) use($service_request_id){
            $quer->where('service_request_id',$service_request_id);
        }])->latest()->get();

        $service_period = ServicePeriodCategory::with(['service_period_category_details_value' => function($query) use($service_request_id){
            $query->where('service_request_id',$service_request_id);
        }])->latest()->get();

        return response()->json([
            'service_request' => new ServiceRequestResource($service_request),
            'checking_input' => new CheckInputCollection($checking_input),
            'service_period_category' => new ServicePeriodCategoryCollection($service_period),
        ]);
    }

    public function updateCheckingInput(Request $request){
        $update_checking_input_value = CheckingInputValue::where('service_request_id',$request->service_request_id)->where('checking_input_id',$request->checking_input_id)->first();
        if (isset($update_checking_input_value) || !empty($update_checking_input_value)){
            $update_checking_input_value->value = $request->value;
            $update_checking_input_value->save();
        }else{
            $create_check_input_value = new CheckingInputValue();
            $create_check_input_value->service_request_id = $request->service_request_id;
            $create_check_input_value->checking_input_id = $request->checking_input_id;
            $create_check_input_value->value = $request->value;
            $create_check_input_value->save();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Value Updated Successfully'
        ]);
    }

    public function getServicePeriodCategory(Request $request){
        $service_request_id = $request->service_request_id;

        $service_period = ServicePeriodCategory::with(['service_period_category_details_value' => function($query) use($service_request_id){
            $query->where('service_request_id',$service_request_id);
        }])->latest()->get();

        return new ServicePeriodCategoryCollection($service_period);
    }

    public function updateServicePeriodCategoryDetails(Request $request){

        if (empty($request->service_request_id) || empty($request->service_period_category_id)) {
            return response()->json([
                'status' => 200,
                'message' => 'Data Not Found'
            ]);
        }

        $update_service_period_details = ServicePeriodCategoryDetails::where('service_request_id',$request->service_request_id)->where('service_period_category_id',$request->service_period_category_id)->first();

        if (isset($update_service_period_details) || !empty($update_service_period_details)){
            $update_service_period_details->current_phase_one = $request->current_phase_one;
            $update_service_period_details->current_phase_two = $request->current_phase_two;
            $update_service_period_details->current_phase_three = $request->current_phase_three;
            $update_service_period_details->rpm = $request->rpm;
            $update_service_period_details->frequency = $request->frequency;
            $update_service_period_details->oil_pressure = $request->oil_pressure;
            $update_service_period_details->cooling_water_temp = $request->cooling_water_temp;
            $update_service_period_details->save();
        }else{
            $create_service_period_details = new ServicePeriodCategoryDetails();
            $create_service_period_details->service_request_id = $request->service_request_id;
            $create_service_period_details->service_period_category_id = $request->service_period_category_id;
            $create_service_period_details->current_phase_one = $request->current_phase_one;
            $create_service_period_details->current_phase_two = $request->current_phase_two;
            $create_service_period_details->current_phase_three = $request->current_phase_three;
            $create_service_period_details->rpm = $request->rpm;
            $create_service_period_details->frequency = $request->frequency;
            $create_service_period_details->oil_pressure = $request->oil_pressure;
            $create_service_period_details->cooling_water_temp = $request->cooling_water_temp;
            $create_service_period_details->save();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Value Updated Successfully'
        ]);
    }

    public function technicianCompletedServiceRequestList(){
        $user = JWTAuth::parseToken()->authenticate();
        $service_Request = ServiceRequest::where('technician_id',$user->id)
            ->where(function ($query){
                $query->where('service_request_status','completed')->orWhere('service_request_status','approved');
            })
            ->with('serviceType','engineer','technician','problem_Section')
            ->paginate(15);
        return new ServiceRequestCollection($service_Request);
    }
}
