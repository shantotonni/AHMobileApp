<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id','desc')->paginate(15);
        return new CustomerCollection($customers);
    }

    public function store(CustomerRequest $request)
    {

        if ($request->has('Image')) {
            $image = $request->Image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/customer/').$name);
        } else {
            $name = 'not_found.jpg';
        }
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->code = $request->code;
        $customer->mobile = $request->mobile;
        $customer->password = bcrypt($request->password);
        $customer->date_of_purchase = $request->date_of_purchase;
        $customer->generator_info_id = $request->generator_info_id;
        $customer->image = $name;
        $customer->status = 'Y';

        return response()->json(['message'=>'Customer Created Successfully'],200);
    }

    public function update(CustomerRequest $request, $id)
    {
        $customer = Customer::where('id',$id)->first();
        $image = $request->Image;

        if ($request->has('Image')) {
            //code for remove old file
            if ($customer->image != '' && $customer->image != null) {
                $destinationPath = 'images/customer/';
                $file_old = public_path('/').$destinationPath . $customer->image;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
            }
            $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->resize(200,250)->save(public_path('images/customer/') . $name);
        } else {
            $name = $customer->image;
        }

        $customer->name = $request->name;
        $customer->code = $request->code;
        $customer->mobile = $request->mobile;
        $customer->date_of_purchase = $request->date_of_purchase;
        $customer->generator_info_id = $request->generator_info_id;
        $customer->image = $name;
        $customer->Active = 'Y';

        return response()->json(['message'=>'Customer Updated Successfully'],200);
    }

    public function destroy($id)
    {
        //
    }

    public function search($query)
    {
        $customers = Customer::where('CustomerName','LIKE',"%$query%")
            ->orWhere('CustomerCode', 'like', '%' . $query . '%')
            ->orderBy('CustomerID','desc')->paginate(10);
        return new CustomerCollection($customers);
    }
}
