<?php

namespace App\Http\Controllers\Api_V2_0\ProductCompany;

use App\Http\Controllers\Controller;
use App\Models\Api_V2_0\ProductCompany;
use App\Models\Api_V2_0\ProductTableVersion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $product = ProductCompany::with(['ml_products_table_versions_prices','ml_products_table_versions_prices.ml_products', 'ml_products_table_versions_prices.ml_products.tipoproduto',  'ml_products_table_versions_prices.ml_products.category'])
            ->where('active', true)
            ->where('empresas_id', $empresa_id);

        if ($request->description) {
            $product->whereHas('ml_products_table_versions_prices.ml_products', function (Builder $builder) use ($request) {
                $builder->where('description', 'like', $request->description ? '%' . $request->description . '%' : '');
            });
        }

        if ($request->paginate) {
            $product = $product->paginate($request['per_page'] ? $request['per_page'] : 15); //->sortBy('orcamento.homecare.paciente.pessoa.nome');
        } else {
            $product = $product->get();
        }

        if (env("APP_ENV", 'production') == 'production') {
            return $product->withPath(str_replace('http:', 'https:', $product->path()));
        } else {
            return $product;
        }

        return $product;
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
            foreach ($request['product'] as $key => $product) {
                $prod = ProductTableVersion::find($product["product_table_versions_prices_id"]);
                $productCompany =  ProductCompany::updateOrCreate(
                    [
                        'product_table_versions_prices_id'  => $product['product_table_versions_prices_id'],
                    ],
                    [
                        'empresas_id'                       => $empresa_id,
                        'price'                             => $prod->price,
                        'cost'                              => $product['cost'],
                        'stock'                             => $product['stock'],
                        'internal_code'                     => $request['internal_code'],
                        'barcode'                           => $request['barcode'],
                        'validity'                          => $request['validity'],
                        'group'                             => $request['group'],
                        'expenditure'                       => $request['expenditure'],
                        'observations'                      => $request['observations'],
                        'cost_value'                        => $request['cost_value'],
                        'sale_value'                        => $request['sale_value'],
                        'percentage_annual_devaluation'     => $request['percentage_annual_devaluation'],
                        'estimated_final_value'             => $request['estimated_final_value'],
                        'minimum_stock'                     => $request['minimum_stock'],
                        'maximum_stock'                     => $request['maximum_stock'],
                        'current_quantity'                  => $request['current_quantity'],
                        'physical_location'                 => $request['percentage_annual_devaluation'],
                        'batch_control'                     => $request['batch_control'],
                        'marcas_id'                     => $request['marcas_id'],
                    ]
                );
            }
        });
        return response()->json('CADASTRADO E ATUALIZADO!', 400)->header('Content-Type', 'text/plain');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCompany $productCompany)
    {
        // $empresa_id = $request->user()->pessoa->profissional->empresa_id;

        // $table_version = ProductCompany::where('product_table_versions_prices_id', $request['product_table_versions_prices_id']);

        // if ($table_version) {

        //     $products = ProductCompany::where('ml_products_table_versions_prices.products_id', '=', 'ml_products.id');

        //     if ($products) {
        //         DB::transaction(function () use ($request, $empresa_id, $productCompany) {
        //             foreach ($request['product'] as $key => $product) {
        //                 $productCompany->update(
        //                     [
        //                         'empresas_id'                       => $empresa_id,
        //                         'product_table_versions_prices_id'  => $product['product_table_versions_prices_id'],
        //                         'price'                             => $product['price'],
        //                         'cost'                              => $product['cost'],
        //                         'stock'                             => $product['stock']
        //                     ]
        //                 );
        //             }
        //         });
        //         return response()->json('ATUALIZADO!', 400)->header('Content-Type', 'text/plain');
        //     }
        // } else {
        //     $this->store($request);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCompany $productCompany)
    {
        $productCompany->active = false;
        $productCompany->save();
    }
}
