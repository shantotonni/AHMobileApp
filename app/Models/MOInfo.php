<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MOInfo extends Model
{
    use HasFactory;
    protected $table = 'MOInfo';
    public $primaryKey='ID';
    protected $guarded = [];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'UpdatedDate';

    public function district(){
        return $this->belongsTo(District::class,'DistrictId','ID');
    }
    public function upazila(){
        return $this->belongsTo(Upazila::class,'UpazilaId','ID');
    }
}
