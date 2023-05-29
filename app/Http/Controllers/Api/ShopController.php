<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ShopStoreRequest;
use App\Http\Requests\Shop\ShopUpdateRequest;
use App\Http\Resources\Shop\ShopCollection;
use App\Models\Shop;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('district','upazila')->orderBy('ID', 'desc')->paginate(15);
        return new ShopCollection($shops);
    }

    public function store(ShopStoreRequest $request)
    {
        $shop = new Shop();
        $shop->ShopName = $request->ShopName;
        $shop->ShopOwnerName = $request->ShopOwnerName;
        $shop->ShopOwnerMobile = $request->ShopOwnerMobile;
        $shop->ShopAddress = $request->ShopAddress;
        $shop->DistrictID = $request->DistrictID;
        $shop->UpazilaID = $request->UpazilaID;
        $shop->save();
        return response()->json(['message' => 'Shop Created Successfully'], 200);
    }

    public function update(ShopUpdateRequest $request, $id)
    {
        $shop = Shop::where('ID', $id)->first();
        $shop->ShopOwnerName = $request->ShopOwnerName;
        $shop->ShopName = $request->ShopName;
        $shop->ShopOwnerMobile = $request->ShopOwnerMobile;
        $shop->ShopAddress = $request->ShopAddress;
        $shop->DistrictID = $request->DistrictID;
        $shop->UpazilaID = $request->UpazilaID;
        $shop->save();
        return response()->json(['message' => 'Shop Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        Shop::where('ID', $id)->delete();
        return response()->json(['message' => 'Shop Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new ShopCollection(Shop::where('ShopName', 'LIKE', "%$query%")
            ->latest()->paginate(20));
    }
}
