<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\TestRequest;
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
     $results = TestRequest::where('user_id', '=', 1)
     ->where('branch','=', $request->get('branch'))
     ->where('repo_id', '=', $request->get('repo_id'))
     ->with('phpstanResult', 'phpSecurityCheckerResult','composerAuditResult')
     ->get();
     $phpstan = $results->groupBy('phpstanResult');
    return response()->json($phpstan);
  } 
    
}
