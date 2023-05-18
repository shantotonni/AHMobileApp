<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Thana\ThanaStoreRequest;
use App\Http\Requests\Thana\ThanaUpdateRequest;
use App\Http\Resources\Thana\ThanaCollection;
use App\Models\Thana;
use Illuminate\Http\Request;

class ThanaController extends Controller
{
    public function index()

    {
        $thanas = Thana::orderBy('ID', 'desc')->paginate(15);
        return new ThanaCollection($thanas);
    }

    public function store(ThanaStoreRequest $request)
    {

        $thana = new Thana();
        $thana->ThanaCode = $request->ThanaCode;
        $thana->ThanaName = $request->ThanaName;
        $thana->ThanaNameBn = $request->ThanaNameBn;
        $thana->save();
        return response()->json(['message' => 'Thana Created Successfully'], 200);
    }

    public function update(ThanaUpdateRequest $request, $id)
    {
        $thana = Thana::where('ID', $id)->first();
        $thana->ThanaCode = $request->ThanaCode;
        $thana->ThanaName = $request->ThanaName;
        $thana->ThanaNameBn = $request->ThanaNameBn;
        $thana->save();
        return response()->json(['message' => 'Thana Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        Thana::where('ID', $id)->delete();
        return response()->json(['message' => 'Thana Deleted Successfully'], 200);
    }
    public function getAllThana(){
        $thanas = Thana::orderBy('ID','desc')->get();
        return response()->json([
            'thanas'=>$thanas
        ]);
    }
    public function search($query)
    {
        return new ThanaCollection(Thana::where('ThanaName', 'LIKE', "%$query%")->latest()->paginate(20));
    }
}
