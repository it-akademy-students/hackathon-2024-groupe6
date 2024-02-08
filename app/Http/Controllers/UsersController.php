<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserRessource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Update the connected user
     *
     * @param UpdateUserRequest $request
     * @return void
     */
    public function update(UpdateUserRequest $request)
    {
        $user = User::find($request->id);
        $data = $request->validated();

        $user->update($data);

        return new UserRessource($user);
    }

    /**
     * Get the current connected user
     * @return UserRessource
     */
    public function getAuthUser(): UserRessource
    {
        $user = auth('sanctum')->user();

        return new UserRessource($user);
    }
}
