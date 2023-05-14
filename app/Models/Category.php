<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'Category';
    public $primaryKey = 'ID';
    protected $guarded = [];
    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'UpdatedDate';

    public function portfolio(){
        return $this->belongsTo(Portfolio::class,'PortfolioID','ID');
    }
}
