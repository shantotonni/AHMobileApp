<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio  extends Model
{
    use  HasFactory;
    protected $table = 'Portfolio';
    public $primaryKey = 'ID';
    protected $guarded = [];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'UpdatedDate';
    public function Portfolio(){
        return $this->hasMany(Portfolio::class,'ID','PortfolioID');
    }
}
