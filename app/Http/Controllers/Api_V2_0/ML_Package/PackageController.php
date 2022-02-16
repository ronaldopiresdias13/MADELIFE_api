<?php

namespace App\Http\Controllers\Api_V2_0\ML_Package;

use App\Http\Controllers\Controller;
use App\Models\Api_V2_0\Package;
use App\Models\Api_V2_0\PackageItems;
use App\Models\Api_V2_0\PackageProduct;
use App\Models\Api_V2_0\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $package = Package::with([
            'ml_packages_product', 'ml_packages_product.ml_products_company.ml_products_table_versions_prices.ml_products',
            'ml_packages_services', 'ml_packages_services.servico'
        ])
            ->where('empresas_id', $empresa_id)
            ->get();

        return $package;
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

        DB::transaction(function () use ($request, $empresa_id) {
            $package = Package::create([
                'empresas_id'   => $empresa_id,
                'code'          => $request['code'],
                'description'   => $request['description'],
            ]);

            if ($request['ml_packages_product']) {
                foreach ($request['ml_packages_product'] as $key => $packageProduct) {
                    PackageProduct::create(
                        [
                            'packages_id'           => $package->id,
                            'product_company_id'    => $packageProduct['product']['id'],
                            'quantity'              => $packageProduct['quantity'],
                            'unitary_value'         => $packageProduct['unitary_value'],
                            'subtotal'              => $packageProduct['subtotal'],
                            'cost'                  => $packageProduct['cost'],
                            'subtotal_cost'         => $packageProduct['subtotal_cost'],
                            'monthly_result_value'  => $packageProduct['monthly_result_value'],
                            'monthly_cost_value'    => $packageProduct['monthly_cost_value'],
                            'lease'                 => $packageProduct['lease'],
                        ]
                    );
                }
            }

            if ($request['packageServices']) {
                foreach ($request['packageServices'] as $key => $packageService) {
                    PackageService::create(
                        [
                            'packages_id'               => $package->id,
                            'servico_id'                => $packageService['servico_id'],
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
        // return response()->json('CADASTRADO!', 400)->header('Content-Type', 'text/plain');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        return Package::with([
            'ml_packages_product', 'ml_packages_product.ml_products_company.ml_products_table_versions_prices.ml_products',
            'ml_packages_services', 'ml_packages_services.servico'
        ])->find($package->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        DB::transaction(function () use ($request, $empresa_id, $package) {
            $package = Package::updateOrCreate(
                [
                    'id' => $request['id'],
                ],
                [
                    'code'                      => $request['code'],
                    'description'               => $request['description'],
                    'empresas_id'               => $empresa_id,
                ]
            );
            if ($request['ml_packages_product']) {
                foreach ($request['ml_packages_product'] as $key => $packageProduct) {
                    PackageProduct::updateOrCreate(
                        [
                            'id'                    => $packageProduct['id'],
                        ],
                        [
                            'packages_id'           => $package->id,
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

            if ($request['packageServices']) {
                foreach ($request['packageServices'] as $key => $packageService) {
                    PackageService::updateOrCreate(
                        [
                            'id'                        => $packageService['id'],
                        ],
                        [
                            'packages_id'               => $package->id,
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

    public function excluirItemPacoteServico(PackageService $packageService)
    {
        $packageService->delete();
    }
    public function excluirItemPacoteProduto(PackageProduct $packageProduct)
    {
        $packageProduct->delete();
    }
}
