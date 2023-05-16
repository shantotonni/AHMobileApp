<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuPermissionController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\Mobile\EngineerController;
use App\Http\Controllers\Api\Mobile\ServiceRequestController;
use App\Http\Controllers\Api\Mobile\SparesController;
use App\Http\Controllers\Api\Mobile\TechnicianController;
use App\Http\Controllers\Api\Mobile\WarehouseController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SettingController;
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
    Route::get('search/user/{query}', [UserController::class,'search']);
    Route::get('get-all-users/', [UserController::class, 'getAllUser']);
    Route::get('user-by-user-id', [UserController::class, 'getUserByUserId']);
    Route::post('user-profile-update', [UserController::class, 'updateProfile']);

    //menu resource route
    Route::apiResource('menu', MenuController::class);
    Route::get('search/menu/{query}', [MenuController::class,'search']);
    Route::get('get-all-menu', [MenuController::class,'getAllMenu']);

    //menu permission route
    Route::get('get-user-menu-details/{UserID}', [MenuPermissionController::class, 'getUserMenuPermission']);
    Route::get('sidebar-get-all-user-menu', [MenuPermissionController::class,'getSidebarAllUserMenu']);
    Route::post('save-user-menu-permission', [MenuPermissionController::class,'saveUserMenuPermission']);

    //customer resource route
    Route::apiResource('customer', CustomerController::class);
    Route::get('search/customer/{query}', [CustomerController::class,'search']);

    //Role resource route
    Route::apiResource('role', RoleController::class);
    Route::get('search/role/{query}', [RoleController::class,'search']);

    //Brand resource route
    Route::apiResource('brand', BrandController::class);
    Route::get('search/brand/{query}', [BrandController::class,'search']);

    //change-password
    Route::post('change-password', [SettingController::class,'changePassword']);


    //For mobile route
    //store generator and delivery info route
    Route::post('generator-info-store', [WarehouseController::class,'generatorInfoStore']);
    Route::post('delivery-info-store', [WarehouseController::class,'deliveryInfoStore']);

    //common route
    Route::get('get-all-generator-info-for-warehouse', [CommonController::class,'getAllGeneratorInfoForWarehouse']);
    Route::get('get-all-generator-info', [CommonController::class,'getAllGeneratorInfo']);
    Route::get('get-all-technician', [CommonController::class,'getAllTechnician']);
    Route::get('get-all-service-type', [CommonController::class,'getAllServiceType']);
    Route::get('get-all-problem-section', [CommonController::class,'getAllProblemSection']);
    Route::get('get-all-question', [CommonController::class,'getAllQuestion']);

    //delivery process route route
    Route::post('add-technician-to-delivery', [EngineerController::class,'addTechnicianToDelivery']);
    Route::get('engineer-all-pending-delivery-list', [EngineerController::class,'EngineerAllPendingDeliveryList']);
    Route::get('engineer-all-delivery-list', [EngineerController::class,'EngineerAllDeliveryList']);

    //Technician Service Request route
    Route::get('technician-assign-all-delivery-list', [TechnicianController::class,'technicianAssignDeliveryList']);
    Route::post('technician-submit-delivery-info', [TechnicianController::class,'technicianSubmitDeliveryInfo']);
    Route::get('technician-delivery-info-list', [TechnicianController::class,'technicianDeliveryInfoList']);

    //Service Request route
    Route::get('engineer-service-request-list', [ServiceRequestController::class,'engineerServiceRequestList']);
    Route::post('engineer-service-request-create', [ServiceRequestController::class,'engineerServiceRequestCreate']);
    Route::get('technician-pending-service-request-list', [ServiceRequestController::class,'technicianPendingServiceRequestList']);
    Route::post('technician-start-service-request', [ServiceRequestController::class,'technicianStartServiceRequest']);
    Route::post('technician-arrived-at-site-for-service-request', [ServiceRequestController::class,'technicianArrivedSiteForServiceRequest']);
    Route::post('technician-submit-service-request', [ServiceRequestController::class,'technicianSubmitServiceRequest']);
    Route::post('technician-add-additional-generator-info-for-service-request', [ServiceRequestController::class,'technicianAddAdditionalGeneratorInfoServiceRequest']);
    Route::post('get-checking-input', [ServiceRequestController::class,'getCheckingInput']);
    Route::post('get-service-request-by-service-request-id', [ServiceRequestController::class,'getServiceRequestByServiceRequestId']);
    Route::post('update-checking-input', [ServiceRequestController::class,'updateCheckingInput']);
    Route::post('get-service-period-category', [ServiceRequestController::class,'getServicePeriodCategory']);
    Route::post('update-service-period-category-details', [ServiceRequestController::class,'updateServicePeriodCategoryDetails']);
    Route::get('technician-completed-service-request-list', [ServiceRequestController::class,'technicianCompletedServiceRequestList']);

    //spares inquiry route
    Route::get('get-all-spares-inquiry', [SparesController::class,'getAllSparesInquiry']);
    Route::post('add-spares-inquiry', [SparesController::class,'addSparesInquiry']);

    //get dashboard data route
    Route::get('get-all-dashboard-data', [DashboardController::class,'getDashboardAllDara']);
    Route::get('check-expired', [DashboardController::class,'checkExpired']);

    //admin dashboard
    Route::get('get-all-admin-dashboard-data', [AdminDashboardController::class,'getAdminDashboardAllData']);

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

    //Customer Service Request Route
    Route::post('customer-service-request-create', [\App\Http\Controllers\Api\Mobile\CustomerController::class, 'customerCreateServiceRequest']);
    Route::get('customer-service-request-list', [\App\Http\Controllers\Api\Mobile\CustomerController::class, 'customerServiceRequestList']);

    //get data route
    Route::get('get-all-parts', [\App\Http\Controllers\Api\Mobile\CommonController::class, 'getAllParts']);
    Route::get('get-all-generator-basic-info', [\App\Http\Controllers\Api\Mobile\CommonController::class, 'getAllGeneratorBasicInfo']);
});
