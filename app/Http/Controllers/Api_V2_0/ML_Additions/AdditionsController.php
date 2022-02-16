<?php

namespace App\Http\Controllers\Api_V2_0\ML_Additions;

use App\Http\Controllers\Controller;
use App\Models\Api_V2_0\Additions;
use App\Models\Api_V2_0\AdditionsExtension;
use App\Models\Api_V2_0\AdditionsProducts;
use App\Models\Api_V2_0\AdditionsServices;
use App\Models\Api_V2_0\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdditionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $additions = Additions::with(['ml_produtos', 'ml_servicos', 'contract.cliente.pessoa'])
            ->where('company_id', $empresa_id)
            ->get();

        return $additions;
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
            $additions = Additions::create([
                // 'company_id' => $empresa_id,
                // 'packages_id' => $request['packages_id']['id'],
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'additionscol' => $request->additionscol,
                'contracts_id' => $request['contract']['contract']['id'],
                // 'accepted' => $request->accepted,
                // 'status' => $request->status,
                // 'budget_number' => $request->budget_number,
                // 'budget_type' => $request->budget_type,
                // 'process_number' => $request->process_number,
                // 'date' => $request->date,
                // 'situation' => $request->situation,
                // 'quantity' =>  $request->quantity,
                // 'version' => $request->version,
                // 'unity' => $request-> unity
            ])->id;
            $AdditionsExtension = AdditionsExtension::updateOrCreate(
                [
                    'paciente_id' => $request->paciente_id,
                    'additions_id' => $additions,

                ],
                [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'additionscol' => $request->additionscol,
            ]);
            if ($request['produtos']) {
                foreach ($request['produtos'] as $key => $packageProduct) {
                    AdditionsProducts::updateOrCreate(
                        [
                            'id'                    => $packageProduct['id'],
                        ],
                        [
                            'ml_additions_id'       => $additions,
                            'product_company_id'    => $packageProduct['ml_products_company']['id'],
                            'quantity'              => $packageProduct['quantity'],
                            'unitary_value'         => $packageProduct['unitary_value'],
                            'subtotal'              => $packageProduct['subtotal'],
                            'cost'                  => $packageProduct['cost'],
                            'subtotal_cost'         => $packageProduct['subtotal_cost'],
                            'monthly_result_value'  => $packageProduct['monthly_result_value'],
                            'monthly_cost_value'    => $packageProduct['monthly_cost_value'],
                            'lease'              => $packageProduct['lease'],
                        ]
                    );
                }
            }

            if ($request['servicos']) {
                foreach ($request['servicos'] as $key => $packageService) {
                    AdditionsServices::updateOrCreate(
                        [
                            'id'                        => $packageService['id'],
                        ],
                        [
                            'ml_additions_id'       => $additions,
                            'servico_id'                => $packageService['servico']['id'],
                            'quantity'                  => $packageService['quantity'],
                            'billing_basis'             => $packageService['billing_basis'],
                            'frequency'                 => $packageService['frequency'],
                            'unitary_value'             => $packageService['unitary_value'],
                            'subtotal'                  => $packageService['subtotal'],
                            'cost'                      => $packageService['cost'],
                            'day_cost'                  => $packageService['day_cost'],
                            'night_cost'                => $packageService['night_cost'],
                            'subtotal_cost'             => $packageService['subtotal_cost'],
                            'monthly_result_value'      => $packageService['monthly_result_value'],
                            'monthly_cost_value'        => $packageService['monthly_cost_value'],
                            'day_care_hours'            => $packageService['day_care_hours'],
                            'hours_night_care'          => $packageService['hours_night_care'],
                            'icms'                      => $packageService['icms'],
                            'iss'                       => $packageService['iss'],
                            'inss'                      => $packageService['inss'],
                        ]
                    );
                }
            }
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
     * @param  \App\Models\EmpresaDados  $empresaDados
     * @return \Illuminate\Http\Response
     */
    public function getAdditionsByContract_id(Request $request, Contract $contract)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $contract = Additions::with('ml_servicos.servico', 'ml_produtos.ml_products_company', 'ml_extension')
        ->where('contracts_id', $contract['id'])
            ->get();

        return $contract;
    }
}
