<?php

namespace App\Http\Controllers\Api;

use App\Cnab;
use App\Cnabsantander;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CnabsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Cnab();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Cnab::where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                }
            }
        } else {
            $itens = Cnab::where('id', 'like', '%');
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna']) ? $order['coluna'] : 'id',
                    ($order['tipo']) ? $order['tipo'] : 'asc'
                );
            }
        }

        $itens = $itens->get();

        if ($request['adicionais']) {
            foreach ($itens as $key => $iten) {
                foreach ($request['adicionais'] as $key => $adicional) {
                    if (is_string($adicional)) {
                        $iten[$adicional];
                    } else {
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) {
                                if ($iten[0] == null) {
                                    $iten2 = $iten[$a];
                                } else {
                                    foreach ($iten as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            } else {
                                if ($iten2[0] == null) {
                                    $iten2 = $iten2[$a];
                                } else {
                                    foreach ($iten2 as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $itens;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request, $cnab) {
            if ($request['cnabsantander']['tipo'] == 'Folha') {
                foreach ($request['cnabsantander'][''] as $key => $value) {
                    # code...
                }
            }
            $cnabsantander = Cnabsantander::crete([
                'cnab_id' => Cnab::create([
                    'empresa_id' => $request['empresa_id'],
                    'data'       => $request['data']

                ])->id,
                'tipo' => ['tipo'],
                'cnabheaderarquivo_id'

            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cnab  $cnab
     * @return \Illuminate\Http\Response
     */
    public function show(Cnab $cnab)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cnab  $cnab
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cnab $cnab)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cnab  $cnab
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cnab $cnab)
    {
        //
    }
}
