<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'Product';
    public $primaryKey='ID';
    protected $guarded = [];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'UpdatedDate';

    public function category(){
        return $this->belongsTo(Category::class,'CategoryID','ID');
    }
    public function portfolio(){
        return $this->belongsTo(Portfolio::class,'PortfolioID','ID');
    }
}
