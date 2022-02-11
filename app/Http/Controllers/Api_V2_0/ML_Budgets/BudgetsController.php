<?php

namespace App\Http\Controllers\Api_V2_0\ML_Budgets;

use App\Http\Controllers\Controller;
use App\Models\Api_V2_0\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $budgets = Budget::with(['package', 'cidade'])
            ->where('empresas_id', $empresa_id)
            ->get();

        return $budgets;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;

        DB::transaction(function () use ($request, $empresa_id){
            $budget = Budget::create([
                'company_id' => $empresa_id,
                'packages_id' => $request->packages_id,
                'city_id' => $request->city_id,
                'addition_code' => $request->addition_code,
                'description' => $request->description,
                'objective' => $request->objective,
                'accepted' => $request->accepted,
                'status' => $request->status,
                'budget_number' => $request->budget_number,
                'budget_type' => $request->budget_type,
                'process_number' => $request->process_number,
                'date' => $request->date,
                'situation' => $request->situation,
                'quantity' =>  $request->quantity,
                'version' => $request->version,
                'unity' => $request-> unity
            ]);
        });
        return response()->json([
            'alert' => [
                'title' => 'Ok!',
                'text' => 'Orçamento cadastrado com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Budget $budget)
    {
        $budget->package;
        $budget->cidade;
        return $budget;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Budget $budget)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;

        DB::transaction(function () use ($request, $budget, $empresa_id){
            $budget->update([
                'company_id' => $empresa_id,
                'packages_id' => $request->packages_id,
                'city_id' => $request->city_id,
                'addition_code' => $request->addition_code,
                'description' => $request->description,
                'objective' => $request->objective,
                'accepted' => $request->accepted,
                'status' => $request->status,
                'budget_number' => $request->budget_number,
                'budget_type' => $request->budget_type,
                'process_number' => $request->process_number,
                'date' => $request->date,
                'situation' => $request->situation,
                'quantity' =>  $request->quantity,
                'version' => $request->version,
                'unity' => $request-> unity
            ]);
        });
        return response()->json([
            'alert' => [
                'title' => 'Ok!',
                'text' => 'Orçamento cadastrado com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
