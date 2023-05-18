<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use  HasFactory;
    protected $table = 'Doctor';
    public $primaryKey = 'ID';
    protected $guarded = [];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'UpdatedDate';

    public function district(){
        return $this->belongsTo(District::class,'District','ID');
    }
    public function thana(){
        return $this->belongsTo(Thana::class,'Thana','ID');
    }
}
