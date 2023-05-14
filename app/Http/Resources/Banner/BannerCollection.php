<?php

namespace App\Http\Resources\Banner;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($category){
                return [
                    'ID'=>$category->ID,
                    'BannerName'=>$category->BannerName,
                    'BannerImage'=>$category->BannerImage,
                ];
            })
        ];
    }
}
