<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientPatientRequest;
use App\Models\ClientPatient;
use Illuminate\Http\Request;

class ClientPatientController extends Controller
{
    public function store(ClientPatientRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $patient=new ClientPatient();

        $patient->fill($data);
        $patient->fill(['empresa_id'=>$empresa_id])->save();

        return response()->json([
            'patient' => $patient
        ]);
    }
}
