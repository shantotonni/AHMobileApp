<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerStoreRequest;
use App\Http\Requests\Banner\BannerUpdateRequest;
use App\Http\Resources\Banner\BannerCollection;
use App\Models\Banner;
use Intervention\Image\Facades\Image;


class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('ID', 'desc')->paginate(15);
        return new BannerCollection($banners);
    }

    public function store(BannerStoreRequest $request)
    {
        if ($request->has('BannerImage')) {
            $BannerImage = $request->BannerImage;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($BannerImage, 0, strpos($BannerImage, ';')))[1])[1];
            Image::make($BannerImage)->save(public_path('images/banner/') . $name);
        } else {
            $name = 'not_found.jpg';
        }
        $banner = new Banner();
        $banner->BannerName = $request->BannerName;
        $banner->BannerImage = $name;
        $banner->save();

        return response()->json(['message' => 'Banner Created Successfully'], 200);
    }

    public function update(BannerUpdateRequest $request, $id)
    {

        $banner = Banner::where('ID', $id)->first();
        $BannerImage = $request->BannerImage;
        if ($BannerImage != $banner->BannerImage) {
            if ($request->has('BannerImage')) {
                $destinationPath = 'images/banner/';
                $file_old = public_path('/') . $destinationPath . $banner->BannerImage;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($BannerImage, 0, strpos($BannerImage, ';')))[1])[1];
                Image::make($BannerImage)->save(public_path('images/banner/') . $name);
            }else {
                $name = $banner->BannerImage;
            }
        }else {
            $name = $banner->BannerImage;
        }


        $banner->BannerName = $request->BannerName;
        $banner->BannerImage = $name;
        $banner->save();
        return response()->json(['message' => 'Banner Updated Successfully'], 200);
    }


    public function destroy($id)
    {
        Banner::where('ID', $id)->delete();
        return response()->json(['message' => 'Banner Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new BannerCollection(Banner::where('BannerName', 'LIKE', "%$query%")->latest()->paginate(20));
    }
}
