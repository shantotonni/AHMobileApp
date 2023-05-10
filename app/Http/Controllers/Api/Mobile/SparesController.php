<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\SparesInquiry\SparesInquiryRequest;
use App\Http\Resources\SparesInquiry\SparesInquiryCollection;
use App\Models\SparesInquiry;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class SparesController extends Controller
{
    public function getAllSparesInquiry()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $spares_inquiry = SparesInquiry::where(function ($query) use($user){
            if ($user->role_id == 3){
                $query->where('technician_id',$user->id);
            }
        })->paginate();

        return new SparesInquiryCollection($spares_inquiry);
    }

    public function addSparesInquiry(SparesInquiryRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $spares_inquiry = new SparesInquiry();
        $spares_inquiry->technician_id = $user->id;
        $spares_inquiry->customer_name = $request->customer_name;
        $spares_inquiry->location = $request->location;
        $spares_inquiry->contact_details = $request->contact_details;
        $spares_inquiry->required_spares = $request->required_spares;
        $spares_inquiry->save();
        return response()->json(['message' => 'Spares Inquiry Submitted successfully','status'=>200 ], 200);
    }

}
