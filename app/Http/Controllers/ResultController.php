<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\TestRequest;
use App\Models\Repository;
use Illuminate\Http\Request;


class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }

    public function getResultByBranch(Request $request)
    {
        $repository = Repository::where('user_id', '=', auth('sanctum')->user()->id)
        ->with(
          'testRequests',
          function ($query) {
            $query->with('phpstanResult', function ($ttt) {
                $ttt->with('status');
            });
            $query->with('phpSecurityCheckerResult', function ($ttt) {
                $ttt->with('status');
            });
            $query->with('composerAuditResult', function ($ttt) {
                $ttt->with('status');
            });
          }
        )
        ->first();
        return response()->json($repository);
  } 
    
}
