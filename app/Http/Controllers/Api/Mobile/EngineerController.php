<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryInfo\DeliveryInfoCollection;
use App\Models\DeliveryInfo;
use App\Models\GeneratorInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class EngineerController extends Controller
{
    public function addTechnicianToDelivery(Request $request){

        if ($request->technician_id && $request->delivery_info_id){
            $user = JWTAuth::parseToken()->authenticate();
            $delivery_info = DeliveryInfo::where('id',$request->delivery_info_id)->first();

            $delivery_info->technician_id        = $request->technician_id;
            $delivery_info->engineer_id          = $user->id;
            $delivery_info->generator_info       = $request->generator_info;
            $delivery_info->site_name            = $request->site_name;
            $delivery_info->location             = $request->location;
            $delivery_info->contact_person_name  = $request->contact_person_name;
            $delivery_info->contact_number       = $request->contact_number;
            $delivery_info->delivery_assign_date = Carbon::now();
            $delivery_info->delivery_status      = 'ongoing';

            if ($delivery_info->save()){
                $generator_info = GeneratorInfo::where('id',$delivery_info->generator_info_id)->first();
                $generator_info->delivery_status = 'ongoing';
                $generator_info->save();

                $data = [];
                $data['title'] = 'New Delivery Request';
                $data['body'] = 'You Have been assigned to a New Delivery Request';
                $data['action'] = 'delivery';
                $technician = User::where('id',$request->technician_id)->pluck('id');
                sendNotification($data,$technician);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Technician assign successfully'
            ]);

        }else{
            return response()->json([
                'status' => 200,
                'message' => 'Delivery Not Found'
            ]);
        }
    }

    public function EngineerAllPendingDeliveryList(){
        $delivery_list = DeliveryInfo::where('delivery_status','pending')->with('generatorInfo','engineer','technician')->orderBy('created_at','desc')->paginate(20);
        return new DeliveryInfoCollection($delivery_list);
    }

    public function EngineerAllDeliveryList(){
        $delivery_list = DeliveryInfo::with('generatorInfo','engineer','technician')->where('delivery_status','!=','pending')
            ->orderBy('created_at','desc')->paginate(20);

        return new DeliveryInfoCollection($delivery_list);
    }
}
