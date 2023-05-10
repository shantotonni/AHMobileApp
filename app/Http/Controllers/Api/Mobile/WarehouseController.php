<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Generator\DeliveryInfoRequest;
use App\Http\Requests\Generator\GeneratorInfoRequest;
use App\Models\DeliveryInfo;
use App\Models\DeliveryInfoImage;
use App\Models\GeneratorInfo;
use App\Models\GeneratorInfoImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Http\Resources\GeneratorInfo\GeneratorInfoResource;
use Tymon\JWTAuth\Facades\JWTAuth;

class WarehouseController extends Controller
{
    public function generatorInfoStore(GeneratorInfoRequest $request){

        DB::beginTransaction();
        try {

            $user = JWTAuth::parseToken()->authenticate();
            $exist_check = GeneratorInfo::where('unique_generator_code',$request->unique_generator_code)->orWhere('generator_serial',$request->generator_serial)->exists();
            if ($exist_check) {
                return response()->json([
                    'status'=>400,
                    'message' => 'Already Exist'
                ], 200);
            }

            $generator_info  = new GeneratorInfo();
            $generator_info->generator_name = $request->generator_name;
            $generator_info->unique_generator_code = $request->unique_generator_code;
            $generator_info->generator_model = $request->generator_model;
            $generator_info->brand_id = $request->brand_id;
            $generator_info->rating = $request->rating;
            $generator_info->generator_serial = $request->generator_serial;
            $generator_info->engine_brand = $request->engine_brand;
            $generator_info->engine_serial = $request->engine_serial;
            $generator_info->alternator_brand = $request->alternator_brand;
            $generator_info->alternator_serial = $request->alternator_serial;
            if ($user->role_id == 4){
                $generator_info->delivery_status = 'pending';
            }else{
                $generator_info->delivery_status = 'delivered';
            }
            $generator_info->created_by = $user->id;
            $generator_info->updated_by = $user->id;

            if ($generator_info->save()) {

                if ($request->hasFile('generator_image')) {
                    foreach ($request->file('generator_image') as $file) {

                        $name = time().'.' . uniqid().'.'.$file->getClientOriginalExtension();
                        Image::make($file)->resize(500,600)->save(public_path('generator/').$name);

                        $image = new GeneratorInfoImage();
                        $image->generator_image = $name;
                        $image->generator_info_id = $generator_info->id;
                        $image->save();
                    }
                } else {
                    return response()->json([
                        'status'=>400,
                        'message' => 'Image Not Found'
                    ], 200);
                }

                DB::commit();
                return response()->json([
                    'status'=>200,
                    'message' => 'Generator Info Successfully Inserted',
                    'generator_info'=> new GeneratorInfoResource($generator_info)
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

    public function deliveryInfoStore(DeliveryInfoRequest $request){

        DB::beginTransaction();

        try {

            $exist_check = DeliveryInfo::where('generator_info_id',$request->generator_info_id)->exists();

            if ($exist_check) {
                return response()->json(['message' => 'Already Exist','status'=>400], 200);
            }

            $delivery_info  = new DeliveryInfo();
            $delivery_info->generator_info_id = $request->generator_info_id;
            $delivery_info->client_name = $request->client_name;
            $delivery_info->delivery_location = $request->delivery_location;
            $delivery_info->truck_number = $request->truck_number;
            $delivery_info->driver_name = $request->driver_name;
            $delivery_info->driver_number = $request->driver_number;
            $delivery_info->order = 1;
            $delivery_info->delivery_status = "pending";
            $delivery_info->delivery_create_date = Carbon::now();

            if ($delivery_info->save()) {
                if ($request->hasFile('delivery_image')) {
                    foreach ($request->file('delivery_image') as $file) {
                        $name = time().'.' . uniqid().'.'.$file->getClientOriginalExtension();
                        Image::make($file)->resize(500,600)->save(public_path('delivery/').$name);

                        $image = new DeliveryInfoImage();
                        $image->deliver_info_image = $name;
                        $image->delivery_info_id = $delivery_info->id;
                        $image->save();
                    }
                } else {
                    return response()->json(['message' => 'Image Not Found','status'=>400], 200);
                }

                $generator_info = GeneratorInfo::where('id',$request->generator_info_id)->first();
                $generator_info->delivery_status = 'processing';
                $generator_info->save();

                $data = [];
                $data['title'] = 'New Delivery Request';
                $data['body'] = 'You Received A New Delivery Request';
                $data['action'] = 'delivery';
                $engineer = User::where('role_id',2)->pluck('id');
                sendNotification($data,$engineer);

                DB::commit();
                return response()->json(['message' => 'Delivery Info Successfully Inserted','status'=>200], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'=>400,
                'message'=>$e->getMessage()
            ],400);
        }
    }

}
