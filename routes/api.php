<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuPermissionController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\MOInfoController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\UpazilaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('dashboard-login', [AuthController::class, 'dashboardLogin']);

Route::group(['middleware' => 'jwtauth:api'], function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    //user api route
    Route::apiResource('user', UserController::class);
    Route::get('search/user/{query}', [UserController::class, 'search']);
    Route::get('get-all-users/', [UserController::class, 'getAllUser']);
    Route::get('user-by-user-id', [UserController::class, 'getUserByUserId']);
    Route::post('user-profile-update', [UserController::class, 'updateProfile']);
    //Portfolio route
    Route::apiResource('portfolio', PortfolioController::class);
    Route::get('search/portfolio/{query}', [PortfolioController::class, 'search']);

    //Portfolio route
    Route::apiResource('category', CategoryController::class);
    Route::get('search/category/{query}', [CategoryController::class, 'search']);

//banner route
    Route::apiResource('banner', BannerController::class);
    Route::get('search/banner/{query}', [BannerController::class, 'search']);

    //product route
    Route::apiResource('product', ProductController::class);
    Route::get('search/product/{query}', [ProductController::class, 'search']);

    //doctor route
    Route::apiResource('doctor', DoctorController::class);
    Route::get('search/doctor/{query}', [DoctorController::class, 'search']);

    //moinfo route
    Route::apiResource('moinfo', MOInfoController::class);
    Route::get('search/moinfo/{query}', [MOInfoController::class, 'search']);

    //shop route
    Route::apiResource('shop', ShopController::class);
    Route::get('search/shop/{query}', [ShopController::class, 'search']);

    //district route
    Route::apiResource('district', DistrictController::class);
    Route::get('search/district/{query}', [DistrictController::class, 'search']);
    //district route
    Route::apiResource('upazila', UpazilaController::class);
    Route::get('search/upazila/{query}', [UpazilaController::class, 'search']);
    Route::get('get-all-upazila', [UpazilaController::class, 'getAllUpazila']);
    Route::get('district-wise-upazila/{ID}', [UpazilaController::class, 'getAllUpazilaByDistrict']);


    //menu resource route
    Route::apiResource('menu', MenuController::class);
    Route::get('search/menu/{query}', [MenuController::class, 'search']);
    Route::get('get-all-menu', [MenuController::class, 'getAllMenu']);

    //menu permission route
    Route::get('get-user-menu-details/{UserID}', [MenuPermissionController::class, 'getUserMenuPermission']);
    Route::get('sidebar-get-all-user-menu', [MenuPermissionController::class, 'getSidebarAllUserMenu']);
    Route::post('save-user-menu-permission', [MenuPermissionController::class, 'saveUserMenuPermission']);

    //customer resource route
    Route::apiResource('customer', CustomerController::class);
    Route::get('search/customer/{query}', [CustomerController::class, 'search']);

    //Role resource route
    Route::apiResource('role', RoleController::class);
    Route::get('search/role/{query}', [RoleController::class, 'search']);

    //Brand resource route
    Route::apiResource('brand', BrandController::class);
    Route::get('search/brand/{query}', [BrandController::class, 'search']);

    //change-password
    Route::post('change-password', [SettingController::class, 'changePassword']);

    //common route
    Route::get('get-all-brand', [CommonController::class, 'getAllBrand']);
    Route::get('get-all-user', [CommonController::class, 'getAllUser']);
    Route::get('get-all-customer', [CommonController::class, 'getAllCustomer']);
    Route::get('get-all-role', [CommonController::class, 'getAllRole']);
    Route::get('get-all-district', [CommonController::class, 'getAllDistrict']);
    Route::get('get-all-upazila', [CommonController::class, 'getAllUpazila']);

    //get dashboard data route
    Route::get('get-all-dashboard-data', [DashboardController::class, 'getDashboardAllDara']);
    Route::get('check-expired', [DashboardController::class, 'checkExpired']);

    //admin dashboard
    Route::get('get-all-admin-dashboard-data', [AdminDashboardController::class, 'getAdminDashboardAllData']);

});

//For Customer
//Customer Login
Route::post('customer-login', [CustomerAuthController::class, 'login']);
Route::post('send-otp-for-registration', [CustomerAuthController::class, 'sendOtp']);
Route::post('check-otp-for-registration', [CustomerAuthController::class, 'checkOtp']);
Route::post('customer-registration', [CustomerAuthController::class, 'registration']);

Route::group(['middleware' => 'CustomerAuth'], function () {
    Route::post('auth/profile-update', [CustomerAuthController::class, 'updateProfile']);
    Route::post('change-password', [CustomerAuthController::class, 'changePassword']);

});

//get data route
Route::get('get-all-banner', [CommonController::class, 'getAllBanner']);
Route::get('get-all-portfolio', [CommonController::class, 'getAllPortfolio']);
Route::get('get-all-category', [CommonController::class, 'getAllCategory']);
Route::get('get-all-product', [CommonController::class, 'getAllProduct']);
Route::get('get-all-doctor', [CommonController::class, 'getAllDoctor']);
Route::get('get-all-shop', [CommonController::class, 'getAllShop']);
Route::get('get-all-moinfo', [CommonController::class, 'getAllMOInfo']);
Route::post('product-details', [\App\Http\Controllers\Api\Mobile\ProductController::class, 'productDetails']);
