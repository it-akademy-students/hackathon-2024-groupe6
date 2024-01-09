<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\RegisterResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthRegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  RegisterRequest
     * @return RegisterResource
     */
    public function register(RegisterRequest $request): RegisterResource
    {
        $data = $request->validated();

        $user = User::create($data);

        return new RegisterResource($user);
    }
}
