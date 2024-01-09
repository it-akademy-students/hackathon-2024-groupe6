<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\RegisterResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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
        $data["password"] = Hash::make($request->password);

        $user = User::create($data);

        return new RegisterResource($user);
    }

    public function login(LoginRequest $request): LoginResource|JsonResponse
    {
       $data = $request->validated();

       $user = User::where('email', $data['email'])->first();

       //dd($data, $user);
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        return new LoginResource($user);
    }
}
