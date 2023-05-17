<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Generator\GeneratorBasicInfoCollection;
use App\Http\Resources\GeneratorInfo\GeneratorInfoCollection;
use App\Http\Resources\ProblemSection\ProblemSectionCollection;
use App\Http\Resources\Question\QuestionCollection;
use App\Http\Resources\ServiceType\ServiceTypeCollection;
use App\Http\Resources\SparePartsCollection;
use App\Http\Resources\Technician\TechnicianCollection;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Doctor;
use App\Models\GeneratorBasicInfo;
use App\Models\GeneratorInfo;
use App\Models\Menu;
use App\Models\Portfolio;
use App\Models\ProblemSection;
use App\Models\Product;
use App\Models\Question;
use App\Models\Role;
use App\Models\ServiceType;
use App\Models\SpareParts;
use App\Models\User;


class CommonController extends Controller
{
    public function getAllBanner(){
        $banners = Banner::orderBy('CreatedDate','desc')->get();
        return response()->json([
            'banners'=>$banners
        ]);
    }

    public function getAllPortfolio(){
        $portfolios = Portfolio::orderBy('CreatedDate','desc')->get();
        return response()->json([
            'portfolios'=>$portfolios
        ]);
    }

    public function getAllCategory(){
        $categories = Category::orderBy('CreatedDate','desc')->get();
        return response()->json([
            'categories'=>$categories
        ]);
    }

    public function getAllDoctor(){
        $doctors = Doctor::orderBy('CreatedDate','desc')->get();
        return response()->json([
            'doctors'=>$doctors
        ]);
    }

    public function getAllProduct(){
        $products = Product::orderBy('CreatedDate','desc')->get();
        return response()->json([
            'products'=>$products
        ]);
    }

}
