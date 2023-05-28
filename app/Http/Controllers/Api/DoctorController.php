<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorStoreRequest;
use App\Http\Requests\Doctor\DoctorUpdateRequest;
use App\Http\Resources\Doctor\DoctorCollection;
use App\Http\Resources\Doctor\DoctorResource;
use App\Models\District;
use App\Models\Doctor;
use App\Models\Thana;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::orderBy('ID', 'desc')->paginate(15);
        return new DoctorCollection($doctors);
    }

    public function store(DoctorStoreRequest $request)
    {
        if ($request->has('DoctorImage')) {
            $DoctorImage = $request->DoctorImage;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($DoctorImage, 0, strpos($DoctorImage, ';')))[1])[1];
            Image::make($DoctorImage)->save(public_path('images/doctor/') . $name);
        } else {
            $name = 'not_found.jpg';
        }

        $doctor = new Doctor();
        $doctor->DoctorName = $request->DoctorName;
        $doctor->DoctorImage = $name;
        $doctor->Designation = $request->Designation;
        $doctor->Mobile = $request->Mobile;
        $doctor->Email = $request->Email;
        $doctor->Details = $request->Details;
        $doctor->AddressOne = $request->AddressOne;
        $doctor->AddressTwo = $request->AddressTwo;
        $doctor->DistrictId = $request->DistrictId;
        $doctor->UpazilaId = $request->UpazilaId;
        $doctor->save();
        return response()->json(['message' => 'Doctor Created Successfully'], 200);
    }


    public function update(DoctorUpdateRequest $request, $id)
    {
        $doctor = Doctor::where('ID', $id)->first();
        $DoctorImage = $request->DoctorImage;
        if ($DoctorImage) {
            $destinationPath = 'images/doctor/';
            $file_old = public_path('/') . $destinationPath . $doctor->DoctorImage;
            if (file_exists($file_old)) {
                unlink($file_old);
            }
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($DoctorImage, 0, strpos($DoctorImage, ';')))[1])[1];
            Image::make($DoctorImage)->save(public_path('images/doctor/') . $name);
        } else {
            $name = $doctor->DoctorImage;
        }
        $doctor->DoctorName = $request->DoctorName;
        $doctor->DoctorImage = $name;
        $doctor->Designation = $request->Designation;
        $doctor->Mobile = $request->Mobile;
        $doctor->Email = $request->Email;
        $doctor->Details = $request->Details;
        $doctor->AddressOne = $request->AddressOne;
        $doctor->AddressTwo = $request->AddressTwo;
        $doctor->DistrictId = $request->DistrictId;
        $doctor->UpazilaId = $request->UpazilaId;
        $doctor->save();
        return response()->json(['message' => 'Doctor Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        Doctor::where('ID', $id)->delete();
        return response()->json(['message' => 'Doctor Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new DoctorCollection(Doctor::where('DoctorName', 'LIKE', "%$query%")->latest()->paginate(20));
    }
}
