<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    use HasFactory;
    protected $table = 'Thana';
    public $primaryKey = 'ID';
    protected $guarded = [];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'UpdatedDate';

    public function thana(){
        return $this->hasMany(Thana::class,'Thana','ID');
    }
}
