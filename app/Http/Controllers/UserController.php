<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Identity verification
     *
     * @param  Request  $request
     * @return User|mixed
     */
    public function auth(Request $request): mixed
    {
        return $request->user();
    }

}
