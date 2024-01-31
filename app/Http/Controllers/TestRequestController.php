<?php

namespace App\Http\Controllers;

use App\Http\Requests\Demand\DemandRequest;
use App\Http\Resources\Demand\RepositoryResource;
use App\Http\Resources\Error\ErrorRessource;
use App\Jobs\CloneRepositoryJob;
use App\Models\TestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestRequestController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TestRequest $demand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestRequest $demand)
    {
        //
    }
}
