<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\BrandRequest;
use App\Http\Resources\Brand\BrandCollection;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index()
    {
        $brands = Brand::orderBy('id','desc')->paginate(15);
        return new BrandCollection($brands);
    }

    public function store(BrandRequest $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->save();
        return response()->json(['message'=>'Brand Created Successfully'],200);
    }

    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::find($id);
        $brand->name = $request->name;
        $brand->save();
        return response()->json(['message'=>'Brand Updated Successfully'],200);
    }

    public function destroy($id)
    {
        Brand::where('id',$id)->delete();
        return response()->json(['message'=>'Brand Deleted Successfully'],200);
    }

    public function search($query)
    {
        return new BrandCollection(Brand::where('name','LIKE',"%$query%")->latest()->paginate(20));
    }
}
