<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['invert_code', 'secure_api', 'receiver_email'];
}
