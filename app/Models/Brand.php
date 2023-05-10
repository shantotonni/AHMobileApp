<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    public $primaryKey = 'id';
    protected $guarded = [];

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
}
