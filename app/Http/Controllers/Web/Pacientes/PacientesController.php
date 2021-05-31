<?php

namespace App\Http\Controllers\Web\Pacientes;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PacientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pacientesOfCliente(Request $request)
    {
        $user = $request->user();

        $result = Pessoa::with([
            'pacientes.homecares.orcamento.ordemservico',
            'pacientes.empresa'
        ])
            ->whereHas('pacientes.homecares.orcamento.cliente.pessoa.user', function (Builder $query) use ($user) {
                $query->where('id', $user->id);
            });

        if ($request['paginate']) {
            $result = $result->orderByDesc('nome')->paginate($request['per_page'] ? $request['per_page'] : 15);

            if (env("APP_ENV", 'production') == 'production') {
                return $result->withPath(str_replace('http:', 'https:', $result->path()));
            } else {
                return $result;
            }
        } else {
            $result = $result->get();
            return $result;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paciente $paciente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paciente $paciente)
    {
        //
    }
}
