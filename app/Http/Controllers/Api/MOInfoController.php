<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MOInfo\MOInfoStoreRequest;
use App\Http\Requests\MOInfo\MOInfoUpdateRequest;
use App\Http\Resources\MOInfo\MOInfoCollection;
use App\Models\MOInfo;
use Illuminate\Http\Request;

class MOInfoController extends Controller
{
    public function index()
    {
        $moinfos = MOInfo::orderBy('ID', 'desc')->paginate(15);
        return new MOInfoCollection($moinfos);
    }

    public function store(MOInfoStoreRequest $request)
    {
        $moinfo = new MOInfo();
        $moinfo->Name = $request->Name;
        $moinfo->Email = $request->Email;
        $moinfo->Mobile = $request->Mobile;
        $moinfo->Address = $request->Address;
        $moinfo->District = $request->District;
        $moinfo->Upazila = $request->Upazila;
        $moinfo->save();
        return response()->json(['message' => 'MOInfo Created Successfully'], 200);
    }

    public function update(MOInfoUpdateRequest $request, $id)
    {
        $moinfo = MOInfo::where('ID', $id)->first();
        $moinfo->Name = $request->Name;
        $moinfo->Email = $request->Email;
        $moinfo->Mobile = $request->Mobile;
        $moinfo->Address = $request->Address;
        $moinfo->District = $request->District;
        $moinfo->Upazila = $request->Upazila;
        $moinfo->save();
        return response()->json(['message' => 'MOInfo Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        MOInfo::where('ID', $id)->delete();
        return response()->json(['message' => 'Doctor Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new MOInfoCollection(MOInfo::where('Name', 'LIKE', "%$query%")
            ->latest()->paginate(20));
    }
}
