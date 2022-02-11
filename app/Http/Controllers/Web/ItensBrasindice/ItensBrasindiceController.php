<?php

namespace App\Http\Controllers\Web\ItensBrasindice;

use App\Http\Controllers\Controller;
use App\Models\ItensBrasindice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItensBrasindiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ItensBrasindice::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            ItensBrasindice::create($request->all());
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItensBrasindice  $itensBrasindice
     * @return \Illuminate\Http\Response
     */
    public function show(ItensBrasindice $itensBrasindice)
    {
        return $itensBrasindice;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItensBrasindice  $itensBrasindice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItensBrasindice $itensBrasindice)
    {
        DB::transaction(function () use ($request, $itensBrasindice) {
            $itensBrasindice->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItensBrasindice  $itensBrasindice
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItensBrasindice $itensBrasindice)
    {
        DB::transaction(function () use ($itensBrasindice) {
            $itensBrasindice->delete();
        });
    }
}
