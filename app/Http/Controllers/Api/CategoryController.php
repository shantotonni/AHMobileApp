<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('ID', 'desc')->with('portfolio')->paginate(15);
        return new CategoryCollection($categories);
    }

    public function store(CategoryStoreRequest $request)
    {
        if ($request->has('CategoryImage')) {
            $CategoryImage = $request->CategoryImage;
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($CategoryImage, 0, strpos($CategoryImage, ';')))[1])[1];
            Image::make($CategoryImage)->save(public_path('images/category/') . $name);
        } else {
            $name = 'not_found_jpg';
        }
        $category = new Category();
        $category->PortfolioID = $request->PortfolioID;
        $category->CategoryName = $request->CategoryName;
        $category->CategoryImage = $name;
        $category->Details = $request->Details;
        $category->Active = 'Y';
        $category->save();
        return response()->json(['message' => 'Category Created Successfully'], 200);
    }

    public function edit($id)
    {
        $PortfolioID = Portfolio::where('PortfolioID', $id)->first();
        return new CategoryResource($PortfolioID);
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::where('ID', $id)->first();
        $CategoryImage = $request->CategoryImage;
        if ($CategoryImage != $category->CategoryImage) {
            if ($request->has('CategoryImage')) {
                //code for remove old file
                if ($category->CategoryImage != '' && $category->CategoryImage != null) {
                    $destinationPath = 'images/category/';
                    $file_old = public_path('/').$destinationPath . $category->CategoryImage;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($CategoryImage, 0, strpos($CategoryImage, ';')))[1])[1];
                Image::make($CategoryImage)->save(public_path('images/category/') . $name);
            } else {
                $name = $category->CategoryImage;
            }
        } else {
            $name = $category->CategoryImage;
        }
        $category->PortfolioID = $request->PortfolioID;
        $category->CategoryName = $request->CategoryName;
        $category->CategoryImage = $name;
        $category->Details = $request->Details;
        $category->Active = 'Y';
        $category->save();
        return response()->json(['message' => 'Category Updated Successfully'], 200);
    }

    public function destroy($id)
    {

        $category = Category::where('ID', $id)->first();
        if ($category) {
            $category = Portfolio::where('PortfolioID', $id)->get();
            foreach ($category as $value) {
                $value->delete();
            }
        }
        Category::where('ID', $id)->delete();

        return response()->json(['message' => 'Category Deleted Successfully'], 200);
    }

    public function search($query)
    {
        return new CategoryCollection(Category::where('CategoryName', 'LIKE', "%$query%")->latest()->paginate(20));
    }


}
