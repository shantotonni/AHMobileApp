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

    public function search($query)
    {
        return new DistrictCollection(District::where('DistrictName', 'LIKE', "%$query%")->latest()->paginate(20));
    }
}
