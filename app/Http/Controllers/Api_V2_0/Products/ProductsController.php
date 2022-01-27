<?php

namespace App\Http\Controllers\Api_V2_0\Products;

use App\Http\Controllers\Controller;
use App\Models\Api_V2_0\Product;
use App\Models\Api_V2_0\ProductCompany;
use App\Models\Api_V2_0\ProductTableVersion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $products = Product::join('ml_products_table_versions_prices', 'ml_products_table_versions_prices.products_id', '=', 'ml_products.id');
        $products->where('empresas_id', $empresa_id);
        // ->where('version', 'like', $request->version ? $request->version : '%')
        // ->where('table_type', 'like', $request->table_type ? '%' . $request->table_type . '%' : '')
        $products->orWhere(function ($query) use ($request) {
            $query
                ->where('empresas_id', "=", null);
            // ->where('version', 'like', $request->version ? $request->version : '%')
            // ->where('table_type', 'like', $request->table_type ? '%' .  $request->table_type . '%' : '');
        });
        if ($request->paginate) {
            $products = $products->paginate($request['per_page'] ? $request['per_page'] : 15); //->sortBy('orcamento.homecare.paciente.pessoa.nome');
        } else {
            $products = $products->get();
        }

        if (env("APP_ENV", 'production') == 'production') {
            return $products->withPath(str_replace('http:', 'https:', $products->path()));
        } else {
            return $products;
        }

        // if (!$request['version'] && !$request['table_type']) {
        //     return response()->json([
        //         'message' => 'Nenhum dos campos foram preenchidos'
        //     ], 401);
        // }

        return $products;
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
            $product = Product::create(
                [
                    'empresas_id'                       => $empresa_id,
                    'table_type'                        => 'propria',
                    'code'                              => $request['code'],
                    'description'                       => $request['description'],
                    'code_apresentation'                => $request['code_apresentation'],
                    'content'                           => $request['content'],
                    'unidademedidas_id'                 => $request['unidademedidas_id'],
                    'marcas_id'                         => $request['marcas_id'],
                    'ean'                               => $request['ean'],
                    'tiss'                              => $request['tiss'],
                    'tuss'                              => $request['tuss'],
                    'product_type_id'                   => $request['product_type_id'],
                    'is_hospital'                       => $request['is_hospital'],
                    'is_generic'                        => $request['is_generic'],
                    'category_id'                       => $request['category_id'],
                ]
            );

            $produtcTable = ProductTableVersion::create(
                [
                    'products_id'               => $product->id,
                    'type'                      => $request['table_version']['type'],
                    'version'                   => 1,
                    'price'                     => $request['table_version']['price'],
                    'price_fraction'            => $request['table_version']['price_fraction'],
                    'price_factory'             => $request['table_version']['price_factory'],
                    'price_fraction_factory'    => $request['table_version']['price_fraction_factory'],
                    'ipi'                       => $request['table_version']['ipi'],
                ]
            );

            ProductCompany::create(
                [
                    [
                        'empresas_id'                       => $empresa_id,
                        'price'                             => $produtcTable->price,
                        'cost'                              => $request['cost'],
                        'stock'                             => $request['stock'],
                        'product_table_versions_prices_id'  => $produtcTable->id
                    ]
                ]
            );
        });
        return response()->json([
            'alert' => [
                'title' => 'Ok!',
                'text' => 'Produto cadastrado com sucesso!'
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
    public function update(Request $request, Product $product)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;

        DB::transaction(function () use ($request, $product, $empresa_id) {
            $product->update([
                'empresa_id'                        => $empresa_id,
                'table_type'                        => 'propria',
                'code'                              => $request['code'],
                'description'                       => $request['description'],
                'code_apresentation'                => $request['code_apresentation'],
                'content'                           => $request['content'],
                'unidademedidas_id'                 => $request['unidademedidas_id'],
                'marcas_id'                         => $request['marcas_id'],
                'ean'                               => $request['ean'],
                'tiss'                              => $request['tiss'],
                'tuss'                              => $request['tuss'],
                'product_type_id'                   => $request['product_type_id'],
                'is_hospital'                       => $request['is_hospital'],
                'is_generic'                        => $request['is_generic'],
                'category_id'                       => $request['category_id'],

            ]);
            $productTable = ProductTableVersion::find($request['table_version']['id']);
            if ($productTable) {
                $productTable->update([
                    'type'                      => $request['table_version']['type'],
                    'version'                   => 1,
                    'price'                     => $request['table_version']['price'],
                    'price_fraction'            => $request['table_version']['price_fraction'],
                    'price_factory'             => $request['table_version']['price_factory'],
                    'price_fraction_factory'    => $request['table_version']['price_fraction_factory'],
                    'ipi'                       => $request['table_version']['ipi'],
                ]);
            }
        });
        return response()->json([
            'alert' => [
                'title' => 'Ok!',
                'text' => 'Produto atualizado com sucesso!'
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
    public function destroy(Product $product)
    {
    }

    public function ProductsFilter(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $products = Product::with('ml_products_table_versions_prices', 'ml_products_table_versions_prices.ml_products_company')
            // ->join('ml_products_table_versions_prices', 'ml_products_table_versions_prices.products_id', '=', 'ml_products.id')
            ->where('empresas_id', $empresa_id)
            // ->where('description', 'like', $request->description ? '%' . $request->description . '%' : '')
            // ->orWhere('version', 'like', $request->version ? $request->version : '')
            // ->orWhere('table_type', 'like', $request->table_type ? $request->table_type : '')
            ->orWhere(function ($query) use ($request) {
                $query
                    ->where('empresas_id', "=", null);
                    // ->where('description', 'like', $request->description ? '%' . $request->description . '%' : '');
                //    ->where('version', 'like', $request->version ? $request->version : '')
                //    ->where('table_type', 'like', $request->table_type ? $request->table_type : '');
            });

        if ($request->paginate) {
            $products = $products->paginate($request['per_page'] ? $request['per_page'] : 15); //->sortBy('orcamento.homecare.paciente.pessoa.nome');
        } else {
            $products = $products->get();
        }

        if (env("APP_ENV", 'production') == 'production') {
            return $products->withPath(str_replace('http:', 'https:', $products->path()));
        } else {
            return $products;
        }
        // if (!$request['version'] && !$request['table_type']) {
        //     return response()->json([
        //         'message' => 'Nenhum dos campos foram preenchidos'
        //     ], 401);
        // }

        // return $products;
    }
}
