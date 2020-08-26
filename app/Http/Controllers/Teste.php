<?php

namespace App\Http\Controllers;

use App\Escala;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $with = [];

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    array_push($with, $adicional);
                } else {
                    $filho = '';
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            $filho = $a;
                        } else {
                            $filho = $filho . '.' . $a;
                        }
                    }
                    array_push($with, $filho);
                }
            }
            $itens = Escala::with($with)->where('ativo', true);
        } else {
            $itens = Escala::where('ativo', true);
        }

        // $itens->where('pontos.ativo', 'like', true);

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
            }
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna']) ? $order['coluna'] : 'id',
                    ($order['tipo']) ? $order['tipo'] : 'asc'
                );
            }
        }

        // if ($request['take']) {
        //     $itens->take($request['take']);
        // }

        // if ($request['limit']) {
        //     $itens->limit($request['limit']);
        // }

        // $itens->limit(5);

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

        // return $itens->paginate(5);
        return $itens;

        // "where": [
        //     {
        //       "coluna": "empresa_id",
        //       "expressao": "=",
        //       "valor": 1
        //     },
        //     {
        //       "coluna": "ordemservico_id",
        //       "expressao": "=",
        //       "valor": 129
        //     }
        //   ],
        //   "order": [
        //     {
        //       "coluna": "dataentrada",
        //       "tipo": "asc"
        //     }
        //   ],
        //   "adicionais": [
        //     ["prestador", "pessoa", "conselhos"],
        //             ["prestador", "servicos"],
        //     "pontos",
        //     "servico"
        //   ]



        $escalas = Escala::where('ativo', true)
            ->where('empresa_id', 1)->where('ordemservico.id', 129)
            ->orderBy('dataentrada')
            ->get();

        foreach ($escalas as $key => $escala) {
            $escala->servico;
        }



        return $escalas;

        // if ($request->commands) {
        //     $request = json_decode($request->commands, true);
        // }

        // if ($request['where']) {
        //     foreach ($request['where'] as $key => $where) {
        //         $itens->where(
        //             ($where['coluna']) ? $where['coluna'] : 'id',
        //             ($where['expressao']) ? $where['expressao'] : 'like',
        //             ($where['valor']) ? $where['valor'] : '%'
        //         );
        //     }
        // }

        // if ($request['order']) {
        //     foreach ($request['order'] as $key => $order) {
        //         $itens->orderBy(
        //             ($order['coluna']) ? $order['coluna'] : 'id',
        //             ($order['tipo']) ? $order['tipo'] : 'asc'
        //         );
        //     }
        // }

        // $itens = $itens->get();

        // if ($request['adicionais']) {
        //     foreach ($itens as $key => $iten) {
        //         foreach ($request['adicionais'] as $key => $adicional) {
        //             if (is_string($adicional)) {
        //                 $iten[$adicional];
        //             } else {
        //                 $iten2 = $iten;
        //                 foreach ($adicional as $key => $a) {
        //                     if ($key == 0) {
        //                         if ($iten[0] == null) {
        //                             $iten2 = $iten[$a];
        //                         } else {
        //                             foreach ($iten as $key => $i) {
        //                                 $i[$a];
        //                             }
        //                         }
        //                     } else {
        //                         if ($iten2[0] == null) {
        //                             $iten2 = $iten2[$a];
        //                         } else {
        //                             foreach ($iten2 as $key => $i) {
        //                                 $i[$a];
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        // return $itens;
    }
}
