<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MOInfo\MOInfoStoreRequest;
use App\Http\Requests\MOInfo\MOInfoUpdateRequest;
use App\Http\Resources\MOInfo\MOInfoCollection;
use App\Models\MOInfo;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class MOInfoController extends Controller
{
    public function index()
    {
        $moinfos = MOInfo::with('district','upazila')->orderBy('ID', 'desc')->paginate(15);
        return new MOInfoCollection($moinfos);
    }

    public function store(MOInfoStoreRequest $request)
    {
        if ($request->has('MOImage')) {
            $MOImage= $request->MOImage;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($MOImage, 0, strpos($MOImage, ';')))[1])[1];
            Image::make($MOImage)->save(public_path('images/MOinfo/') . $name);
        } else {
            $name = 'not_found.jpg';
        }
        $moinfo = new MOInfo();
        $moinfo->Name = $request->Name;
        $moinfo->Email = $request->Email;
        $moinfo->Mobile = $request->Mobile;
        $moinfo->Address = $request->Address;
        $moinfo->MOImage = $name;
        $moinfo->DistrictID = $request->DistrictID;
        $moinfo->UpazilaID = $request->UpazilaID;
        $moinfo->save();
        return response()->json(['message' => 'MOInfo Created Successfully'], 200);
    }

    public function update(MOInfoUpdateRequest $request, $id)
    {
        $moinfo = MOInfo::where('ID', $id)->first();
        $MOImage = $request->MOImage;

        if ($MOImage != $moinfo->MOImage) {
            if ($request->has('MOImage')) {
                $destinationPath = 'images/MOinfo/';
                $file_old = public_path('/') . $destinationPath . $moinfo->MOImage;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($MOImage, 0, strpos($MOImage, ';')))[1])[1];
                Image::make($MOImage)->save(public_path('images/MOinfo/') . $name);
            }else {
                $name = $moinfo->MOImage;
            }
        }else {
            $name = $moinfo->MOImage;
        }

        $moinfo->Name = $request->Name;
        $moinfo->Email = $request->Email;
        $moinfo->Mobile = $request->Mobile;
        $moinfo->Address = $request->Address;
        $moinfo->MOImage = $name;
        $moinfo->DistrictID = $request->DistrictID;
        $moinfo->UpazilaID = $request->UpazilaID;
        $moinfo->save();
        return response()->json(['message' => 'MOInfo Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        $moinfo = MOInfo::where('ID', $id)->first();
        if ($moinfo->MOImage) {
            $destinationPath = 'images/MOinfo/';

            $file = public_path('/') . $destinationPath . $moinfo->MOImage;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $moinfo->delete();
        return response()->json(['message' => 'Moinfo Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new MOInfoCollection(MOInfo::where('Name', 'LIKE', "%$query%")
            ->latest()->paginate(20));
    }
}
