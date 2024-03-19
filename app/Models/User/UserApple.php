<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserApple extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
}
