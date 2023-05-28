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
        $upazilas = Upazila::with('districts')->orderBy('DistrictId', 'asc')->paginate(15);
        return new UpazilaCollection($upazilas);
    }

    public function store(UpazilaStoreRequest $request)
    {
        $upazila = new Upazila();
        $upazila->DistrictId= $request->DistrictId;
        $upazila->UpazilaName = $request->UpazilaName;
        $upazila->UpazilaNameBn = $request->UpazilaNameBn;
        $upazila->Code = $request->Code;
        $upazila->save();
        return response()->json(['message' => 'Upazila Created Successfully'], 200);
    }

    public function update(UpazilaUpdateRequest $request, $id)
    {
        $upazila = Upazila::where('ID', $id)->first();
        $upazila->DistrictId = $request->DistrictId;
        $upazila->UpazilaName = $request->UpazilaName;
        $upazila->UpazilaNameBn = $request->UpazilaNameBn;
        $upazila->Code = $request->Code;
        $upazila->save();
        return response()->json(['message' => 'Upazila Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        Upazila::where('ID', $id)->delete();
        return response()->json(['message' => 'Upazila Deleted Successfully'], 200);
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
}
