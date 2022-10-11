<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;

class UserController extends Controller
{
    /**
     * @return UserResource
     */
    public function user(): UserResource
    {
        return UserResource::make(auth()->user());
    }
}
