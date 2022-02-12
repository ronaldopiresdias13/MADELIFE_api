<?php

namespace App\Http\Controllers\Api_V2_0\ML_Contracts;

use App\Http\Controllers\Controller;
use App\Models\Api_V2_0\Contract;
use Illuminate\Http\Request;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $budgets = Contract::with(['contract.cliente.pessoa'])
            ->where('company_id', $empresa_id)
            ->get();

        return $budgets;
    }

}
