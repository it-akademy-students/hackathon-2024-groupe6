<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\LogoutResource;
use App\Http\Resources\Auth\RegisterResource;
use App\Http\Resources\Error\ErrorRessource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthRegisterController extends Controller
{
  /**
   * Register a new user.
   *
   * @param RegisterRequest
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
        catch (Exception $exception)
        {
            return new ErrorRessource($exception);
        }

    }

  /**
   * Log in a user.
   *
   * @param LoginRequest $request
   * @return LoginResource|ErrorRessource
   */
  public function login(LoginRequest $request): LoginResource|ErrorRessource
  {
    $data = $request->validated();

    $user = User::where('email', $data['email'])->first();

    try {
      if (!$user || !Hash::check($data['password'], $user->password)) {
        $customError = new ErrorRessource();
        $customError->setMessage('Invalid login details');
        $customError->setCode(401);
        return $customError;
      }
      return new LoginResource($user);
    } catch (Exception $exception) {
      return new ErrorRessource($exception);
    }
  }

  /**
   * Delete user Account
   * @param Request $request
   * @return LogoutResource|ErrorRessource
   */
  public function deleteUser(Request $request): LogoutResource|ErrorRessource
  {
    try {
      $user = auth('sanctum')->user();
      $user->tokens()->delete();
      //TODO - supprimer les lignes dans les tables repositories, test_requests, (results?) et cloned repos
      $user->delete();

      return new LogoutResource(null);
    } catch (\Throwable $exception) {
      return new ErrorRessource($exception);
    }
  }

  /**
   * Log out a user.
   *
   * @param LogoutRequest $request
   * @return LogoutResource|ErrorRessource
   */
  public function logout(LogoutRequest $request): LogoutResource|ErrorRessource {
    $data = $request->validated();

    $user = User::where('id', $data['id'])->first();

    try {
      $request->user()->currentAccessToken()->delete();

      return new LogoutResource($user);
    }
    catch (Exception $exception)
    {
      return new ErrorRessource($exception);
    }
  }
}
