<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'Role';
    public $primaryKey = 'ID';
    protected $guarded = [];

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
}
