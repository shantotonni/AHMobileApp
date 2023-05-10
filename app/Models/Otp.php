<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';
    public $primaryKey = 'id';
    protected $guarded = [];

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
}
