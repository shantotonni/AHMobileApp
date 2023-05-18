<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerCollection;
use App\Http\Resources\Generator\GeneratorBasicInfoCollection;
use App\Http\Resources\GeneratorInfo\GeneratorInfoCollection;
use App\Http\Resources\Portfolio\PortfolioCollection;
use App\Http\Resources\ProblemSection\ProblemSectionCollection;
use App\Http\Resources\Question\QuestionCollection;
use App\Http\Resources\ServiceType\ServiceTypeCollection;
use App\Http\Resources\SparePartsCollection;
use App\Http\Resources\Technician\TechnicianCollection;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\District;
use App\Models\Doctor;
use App\Models\GeneratorBasicInfo;
use App\Models\GeneratorInfo;
use App\Models\Menu;
use App\Models\MOInfo;
use App\Models\Portfolio;
use App\Models\ProblemSection;
use App\Models\Product;
use App\Models\Question;
use App\Models\Role;
use App\Models\ServiceType;
use App\Models\Shop;
use App\Models\SpareParts;
use App\Models\Thana;
use App\Models\User;


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

    public function getAllCategory()
    {
        $categories = Category::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function getAllDoctor()
    {
        $doctors = Doctor::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'doctors' => $doctors
        ]);
    }

    public function getAllProduct()
    {
        $products = Product::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'products' => $products
        ]);
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
        return response()->json([
            'districts' => $districts
        ]);
    }

    public function getAllThana()
    {
        $thanas = Thana::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'thanas' => $thanas
        ]);
    }

}
