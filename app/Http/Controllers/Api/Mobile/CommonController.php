<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerCollection;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\District\DistrictCollection;
use App\Http\Resources\Portfolio\PortfolioCollection;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Upazila\UpazilaCollection;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\District;
use App\Models\Doctor;
use App\Models\Menu;
use App\Models\MOInfo;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\Thana;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Http\Request;


class CommonController extends Controller
{
    public function getAllBanner()
    {
        $banners = Banner::orderBy('CreatedDate', 'desc')->get();
        return new BannerCollection($banners);
    }

    public function getAllPortfolio()
    {
        $portfolios = Portfolio::orderBy('CreatedDate', 'desc')->get();
        return new PortfolioCollection($portfolios);
    }

    public function getAllCategory(Request $request)
    {
        $categories = Category::orderBy('CreatedDate', 'desc')->get();
        return new CategoryCollection($categories);
//        $PortfolioID = $request->PortfolioID;
//        $categories = Category::where('PortfolioID',$PortfolioID)->orderBy('CreatedDate', 'desc')->get();
//        return new CategoryCollection($categories);
    }

    public function getAllDoctor()
    {
        $doctors = Doctor::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'doctors' => $doctors
        ]);
    }

    public function getAllProduct(Request $request)
    {
//        $CategoryID = $request->CategoryID;
//        $products = Product::where('CategoryID',$CategoryID)->orderBy('CreatedDate', 'desc')->get();
//        return new ProductCollection($products);

        $products = Product::orderBy('CreatedDate', 'desc')->get();
        return new ProductCollection($products);
    }

    public function getAllBrand()
    {
        $brands = Brand::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'brands' => $brands
        ]);
    }

    public function getAllUser()
    {
        $users = User::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'users' => $users
        ]);
    }

    public function getAllCustomer()
    {
        $customers = Customer::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'customers' => $customers
        ]);
    }

    public function getAllMenu()
    {
        $menus = Menu::orderBy('CreatedAt', 'desc')->get();
        return response()->json([
            'menus' => $menus
        ]);
    }

    public function getAllRole()
    {
        $roles = Role::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'roles' => $roles
        ]);
    }

    public function getAllShop()
    {
        $shops = Shop::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'shops' => $shops
        ]);
    }

    public function getAllMOInfo()
    {
        $moinfos = MOInfo::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'moinfos' => $moinfos
        ]);
    }

    public function getAllDistrict()
    {
        $districts = District::orderBy('CreatedDate', 'desc')->get();
        return new DistrictCollection($districts);
    }

    public function getAllUpazila()
    {
        $upazilas = Upazila::orderBy('CreatedDate', 'desc')->get();
        return new UpazilaCollection($upazilas);
    }

}
