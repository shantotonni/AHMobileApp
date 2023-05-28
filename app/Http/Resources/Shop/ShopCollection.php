<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($shop) {
                return [
                    'ID' => $shop->ID,
                    'ShopName' => $shop->ShopName,
                    'ShopOwnerName' => $shop->ShopOwnerName,
                    'ShopOwnerMobile'=>$shop->ShopOwnerMobile,
                    'ShopAddress' => $shop->ShopAddress,
                    'DistrictID' => $shop->DistrictID,
                    'DistrictName'=>isset($shop->district) ? $shop->district->DistrictName: '',
                    'UpazilaID' => $shop->UpazilaID,
                    'UpazilaName' => isset($shop->upazila) ? $shop->upazila->UpazilaName:'',

                ];
            })
        ];
    }
}
