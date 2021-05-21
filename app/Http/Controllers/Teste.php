<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $user = $request->user();

        $result = Pessoa::with([
            'pacientes'
        ])
            ->whereHas('pacientes.homecares.orcamento.cliente.pessoa.user', function (Builder $query) use ($user) {
                $query->where('id', $user->id);
            });

        $result = $result->orderByDesc('id')->paginate($request['per_page'] ? $request['per_page'] : 15);

        if (env("APP_ENV", 'production') == 'production') {
            return $result->withPath(str_replace('http:', 'https:', $result->path()));
        } else {
            return $result;
        }

        return $result;
    }
}
