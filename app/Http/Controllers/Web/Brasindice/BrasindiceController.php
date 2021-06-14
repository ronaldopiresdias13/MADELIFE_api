<?php

namespace App\Http\Controllers\Web\Brasindice;

use App\Http\Controllers\Controller;
use App\Models\Brasindice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrasindiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Brasindice::all();
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
            Brasindice::create($request->all());
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function show(Brasindice $brasindice)
    {
        return $brasindice;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brasindice $brasindice)
    {
        DB::transaction(function () use ($request, $brasindice) {
            $brasindice->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brasindice  $brasindice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brasindice $brasindice)
    {
        DB::transaction(function () use ($brasindice) {
            $brasindice->delete();
        });
    }
}
