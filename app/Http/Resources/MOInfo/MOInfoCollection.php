<?php

namespace App\Http\Resources\MOInfo;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MOInfoCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($moinfo){
                return [
                    'ID'=>$moinfo->ID,
                    'Name'=>$moinfo->Name,
                    'Email'=>$moinfo->Email,
                    'Mobile'=>$moinfo->Mobile,
                    'Address'=>$moinfo->Address,
                    'DistrictID' => $moinfo->DistrictID,
                    'DistrictName'=>isset($moinfo->district) ? $moinfo->district->DistrictName: '',
                    'UpazilaID' => $moinfo->UpazilaID,
                    'UpazilaName' => isset($moinfo->upazila) ? $moinfo->upazila->UpazilaName:'',

                ];
            })
        ];
    }
}
