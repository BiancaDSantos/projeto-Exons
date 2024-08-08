<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultorRequest;
use App\Models\consultor;

class consultorController extends Controller
{
    public function atualizar(ConsultorRequest $request, $id)
    {    
        $validated = $request->validated();
        $consultor = consultor::find($id);

        if ($consultor) {
            $consultor->update($validated);
            return response()->json($consultor, 200);
        }
        return response()->json(['message' => 'Consultor não encontrado.'], 404);
    }

    public function buscaUm($id)
    {
        $consultor = consultor::find($id);
        if($consultor) {
            return response()->json($consultor);
        }
        return response()->json(['message' => 'Consultor não encontrado.'], 404);
    }
    
    public function buscaTodos()
    {
        $consultor = consultor::all();
        return response()->json($consultor);
    }

    public function cadastrar(ConsultorRequest $request)
    {
        $validated = $request->validated();
        $request = consultor::create($validated);
        return response()->json($request, 201);
    }

    public function deletar($id) 
    {
        $consultor = consultor::find($id);
        if($consultor) {
            $consultor->delete();
            return response()->json(['message' => 'Consultor deletado com sucesso.'], 204);
        } else {
            return response()->json(['message' => 'Consultor não encontrado.'], 404);
        }
    }    
}
