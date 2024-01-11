<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\RegisterResource;
use App\Http\Resources\Error\ErrorRessource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  RegisterResource
     * @return RegisterResource
     */
    public function register(RegisterRequest $request): RegisterResource|ErrorRessource
    {
        $data = $request->validated();
        $data["password"] = Hash::make($request->password);

        try
        {
            $user = User::create($data);
            return new RegisterResource($user);
        }
        catch (\Exception $exception)
        {
            return new ErrorRessource($exception);
        }

    }

    /**
     * Log in a user.
     *
     * @param  LoginRequest
     * @return LoginRequest
     */
    public function login(LoginRequest $request): LoginResource|JsonResponse|ErrorRessource
    {
       $data = $request->validated();

       $user = User::where('email', $data['email'])->first();

       try
       {
           if (!$user || !Hash::check($data['password'], $user->password))
           {
               $response = new ErrorRessource();
               $response -> setMessage('Mot de pas incorrecte.');
               $response -> setCode(401);

               return $response;
           }

           return new LoginResource($user);
       }
       catch (\Exception $exception)
       {
           return new ErrorRessource($exception);
       }

    }
}
