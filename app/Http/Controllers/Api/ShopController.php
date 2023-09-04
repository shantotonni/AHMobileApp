<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ShopStoreRequest;
use App\Http\Requests\Shop\ShopUpdateRequest;
use App\Http\Resources\Shop\ShopCollection;
use App\Models\Shop;
use Intervention\Image\Facades\Image;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('district','upazila')->orderBy('ID', 'desc')->paginate(15);
        return new ShopCollection($shops);
    }

    public function store(ShopStoreRequest $request)
    {
        if ($request->has('ShopImage')) {
            $ShopImage = $request->ShopImage;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($ShopImage, 0, strpos($ShopImage, ';')))[1])[1];
            Image::make($ShopImage)->save(public_path('images/shop/') . $name);
        } else {
            $name = 'not_found.jpg';
        }

        $shop = new Shop();
        $shop->ShopName = $request->ShopName;
        $shop->ShopOwnerName = $request->ShopOwnerName;
        $shop->ShopOwnerMobile = $request->ShopOwnerMobile;
        $shop->ShopAddress = $request->ShopAddress;
        $shop->ShopImage = $name;
        $shop->DistrictID = $request->DistrictID;
        $shop->UpazilaID = $request->UpazilaID;
        $shop->save();
        return response()->json(['message' => 'Shop Created Successfully'], 200);
    }

    public function update(ShopUpdateRequest $request, $id)
    {
        $shop = Shop::where('ID', $id)->first();
        $ShopImage = $request->ShopImage;

        if ($ShopImage != $shop->ShopImage) {
            if ($request->has('ShopImage')) {
                $destinationPath = 'images/shop/';
                $file_old = public_path('/') . $destinationPath . $shop->ShopImage;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($ShopImage, 0, strpos($ShopImage, ';')))[1])[1];
                Image::make($ShopImage)->save(public_path('images/shop/') . $name);
            }else {
                $name = $shop->ShopImage;
            }
        }else {
            $name = $shop->ShopImage;
        }

        $shop->ShopOwnerName = $request->ShopOwnerName;
        $shop->ShopName = $request->ShopName;
        $shop->ShopOwnerMobile = $request->ShopOwnerMobile;
        $shop->ShopAddress = $request->ShopAddress;
        $shop->ShopImage = $name;
        $shop->DistrictID = $request->DistrictID;
        $shop->UpazilaID = $request->UpazilaID;
        $shop->save();
        return response()->json(['message' => 'Shop Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        $shop =  Shop::where('ID', $id)->first();
        if ($shop->ShopImage) {
            $destinationPath = 'images/shop/';

            $file = public_path('/') . $destinationPath . $shop->ShopImage;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $shop->delete();
        return response()->json(['message' => 'Shop Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new ShopCollection(Shop::where('ShopName', 'LIKE', "%$query%")
            ->latest()->paginate(20));
    }
}
