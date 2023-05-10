<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryInfo\DeliveryInfoCollection;
use App\Models\AfterDeliveryInfoImage;
use App\Models\DeliveryInfo;
use App\Models\GeneratorInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class TechnicianController extends Controller
{

    public function technicianAssignDeliveryList(){
        $user = JWTAuth::parseToken()->authenticate();

        $delivery_list = DeliveryInfo::with('generatorInfo','engineer','technician')->where('delivery_status','ongoing')->where('technician_id',$user->id)
            ->orderBy('created_at','desc')->paginate(20);
        return new DeliveryInfoCollection($delivery_list);
    }

    public function technicianSubmitDeliveryInfo(Request $request){

        $user = JWTAuth::parseToken()->authenticate();

        DB::beginTransaction();

        try {

            $delivery_info = DeliveryInfo::where('id',$request->delivery_info_id)->where('technician_id',$user->id)->first();
            $delivery_info->deliver_details = $request->deliver_details;
            $delivery_info->delivery_status = "delivery";
            $delivery_info->delivery_complete_date = Carbon::now();

            if ($delivery_info->save()) {
                if ($request->hasFile('after_delivery_info_image')) {
                    foreach ($request->file('after_delivery_info_image') as $file) {
                        $name = time().'.' . uniqid().'.'.$file->getClientOriginalExtension();
                        Image::make($file)->resize(500,600)->save(public_path('after_delivery/').$name);

                        $image = new AfterDeliveryInfoImage();
                        $image->after_delivery_info_image = $name;
                        $image->delivery_info_id = $delivery_info->id;
                        $image->save();
                    }
                } else {
                    return response()->json(['message' => 'Image Not Found','status'=>400], 200);
                }

                $generator_info = GeneratorInfo::where('id',$delivery_info->generator_info_id)->first();
                $generator_info->delivery_status = 'delivery';
                $generator_info->save();

                $data = [];
                $data['title'] = 'Submitted Delivery Request';
                $data['body'] = 'TSA Submitted Delivery Request';
                $data['action'] = 'delivery_finished';
                $engineer = User::where('role_id',2)->pluck('id');
                sendNotification($data,$engineer);

                DB::commit();
                return response()->json(['message' => 'Delivery Info Submitted successfully','status'=>200], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'=>400,
                'message'=>$e->getMessage()
            ],400);
        }
    }

    public function technicianDeliveryInfoList(){
        $user = JWTAuth::parseToken()->authenticate();

        $delivery_list = DeliveryInfo::with('generatorInfo','engineer','technician')->where('delivery_status','delivered')->where('technician_id',$user->id)
            ->orderBy('created_at','desc')->paginate(20);
        return new DeliveryInfoCollection($delivery_list);
    }


}
