<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($user){
                return [
                    'ID'=>$user->ID,
                    'Name'=>$user->Name,
                    'UserID'=>$user->UserID,
                    'RoleId'=>$user->RoleId,
                    'role_name'=>isset($user->role) ? $user->role->Name : '',
                    'Designation'=>$user->Designation,
                    'Mobile'=>$user->Mobile,
                    'Email'=>$user->Email,
                    'Active'=>$user->Active,
                    'Image'=>$user->Image,
                    'device_token'=>$user->device_token,
                ];
            })
        ];
    }
}
