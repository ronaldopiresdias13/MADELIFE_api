<?php

namespace App\Http\Controllers\Api_V2_0\ML_Package;

use App\Http\Controllers\Controller;
use App\Models\Api_V2_0\Package;
use App\Models\Api_V2_0\PackageItems;
use App\Models\Api_V2_0\PackageProduct;
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
        $package = Package::with(['ml_packages_product'])
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
                'description'   => $request['description']
            ]);

            foreach ($request['packageProducts'] as $key => $packageProduct) {
                PackageProduct::create(
                    [
                        'packages_id'           => $package->id,
                        'product_company_id'    => $packageProduct['product_company_id'],
                        'the_amount'            => $packageProduct['the_amount'],
                        'unitary_value'         => $packageProduct['unitary_value'],
                        'subtotal'              => $packageProduct['subtotal'],
                        'cost'                  => $packageProduct['cost'],
                        'subtotal_cost'         => $packageProduct['subtotal_cost'],
                        'monthly_result_value'  => $packageProduct['monthly_result_value'],
                        'monthly_cost_value'    => $packageProduct['monthly_cost_value'],
                        'location'              => $packageProduct['location'],
                    ]
                );
            }
        });
        return response()->json('CADASTRADO!', 400)->header('Content-Type', 'text/plain');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        $package->ml_packages_product;

        return $package;
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
                    'description'   => $request['description'],
                    'empresas_id'            => $empresa_id,
                ]
            );
            foreach ($request['packageProducts'] as $key => $packageProduct) {
                PackageProduct::updateOrCreate(
                    [
                        'id'                  => $packageProduct['id'],
                    ],
                    [
                        'packages_id'           => $package->id,
                        'product_company_id'    => $packageProduct['product_company_id'],
                        'the_amount'            => $packageProduct['the_amount'],
                        'unitary_value'         => $packageProduct['unitary_value'],
                        'subtotal'              => $packageProduct['subtotal'],
                        'cost'                  => $packageProduct['cost'],
                        'subtotal_cost'         => $packageProduct['subtotal_cost'],
                        'monthly_result_value'  => $packageProduct['monthly_result_value'],
                        'monthly_cost_value'    => $packageProduct['monthly_cost_value'],
                        'location'              => $packageProduct['location'],
                    ]
                );
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
}
