<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompromissoRequest;
use App\Models\Compromisso;
use App\Services\CalculoHoraTrabalhadaService;

class compromissoController extends Controller
{
    
    public function atualizar(CompromissoRequest $request, $id){
        $validated = $request->validated();

        $compromisso = Compromisso::find($id);

        if ($compromisso) {
            $compromisso->update($validated);
            return response()->json($compromisso, 200);
        }
        return response()->json(['message' => 'Compromisso não encontrado.'], 404);
    }

    protected $calculoHoraTrabalhadaService;

    public function __construct(CalculoHoraTrabalhadaService $calculoHoraTrabalhadaService)
    {
        $this->calculoHoraTrabalhadaService = $calculoHoraTrabalhadaService;
    }

    public function calcularValorTotal($id)
    {
        $compromisso = Compromisso::findOrFail($id);
        $resultado = $this->calculoHoraTrabalhadaService->calcularValorTotal($compromisso);

        return response()->json($resultado);
    }

    public function buscaUm($id)
    {
        $compromisso = Compromisso::find($id);
        if($compromisso) {
            return response()->json($compromisso);
        }
        return response()->json(['message' => 'Compromisso não encontrado.'], 404);
    }
    
    public function buscaTodos()
    {
        $compromissos = Compromisso::all();
        return response()->json($compromissos);
    }

    public function cadastrar(CompromissoRequest $request)
    {
        $validated = $request->validated();
        $request = Compromisso::create($validated);
        return response()->json($request, 201);
    }

    public function deletar($id) 
    {
        $compromisso = Compromisso::find($id);
        if($compromisso) {
            $compromisso->delete();
            return response()->json(['message' => 'Compromisso deletado com sucesso.'], 204);
        } else {
            return response()->json(['message' => 'Compromisso não encontrado.'], 404);
        }
    }
}
