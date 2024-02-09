<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Models\Result;
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
        $repository = Repository::where('id', '=', $request->repo_id)
            ->with(
                'testRequests',
                function ($query) use($request) {
                    $query->where('branch', '=', $request->branch);
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
