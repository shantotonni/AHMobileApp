<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\District\DistrictStoreRequest;
use App\Http\Requests\District\DistrictUpdateRequest;
use App\Http\Resources\District\DistrictCollection;
use App\Models\District;

class DistrictController extends Controller
{
    public function index()

    {
        $districts = District::orderBy('ID', 'desc')->paginate(15);
        return new DistrictCollection($districts);
    }

    public function store(DistrictStoreRequest $request)
    {
        $district = new District();
        $district->DistrictName = $request->DistrictName;
        $district->DistrictNameBn = $request->DistrictNameBn;
        $district->save();
        return response()->json(['message' => 'District Created Successfully'], 200);
    }

    public function update(DistrictUpdateRequest $request, $id)
    {
        $district = District::where('ID', $id)->first();
        $district->DistrictName = $request->DistrictName;
        $district->DistrictNameBn = $request->DistrictNameBn;
        $district->save();
        return response()->json(['message' => 'District Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        District::where('ID', $id)->delete();
        return response()->json(['message' => 'District Deleted Successfully'], 200);
    }
    public function search($query)
    {
        return new DistrictCollection(District::where('DistrictName', 'LIKE', "%$query%")->latest()->paginate(20));
    }
}
