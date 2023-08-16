<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Upazila\UpazilaStoreRequest;
use App\Http\Requests\Upazila\UpazilaUpdateRequest;
use App\Http\Resources\Upazila\UpazilaCollection;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    public function index()

    {
        $upazilas = Upazila::with('district')->orderBy('DistrictID', 'asc')->paginate(15);
        return new UpazilaCollection($upazilas);
    }

    public function getAllUpazila(Request $request){
        $upazilas = Upazila::OrderBy('ID', 'ASC')->get();
        return response()->json([
            'upazilas'=>$upazilas
        ]);
    }
    public function search($query)
    {
        return new UpazilaCollection(Upazila::where('UpazilaName', 'LIKE', "%$query%")->latest()->paginate(20));
    }

    public function getAllUpazilaByDistrict($ID){

        return new UpazilaCollection(Upazila::where('DistrictID', $ID)->paginate(20));
    }

}
