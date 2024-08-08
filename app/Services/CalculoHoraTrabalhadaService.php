<?php

namespace App\Services;

use App\Models\Compromisso;
use Carbon\Carbon;

class CalculoHoraTrabalhadaService
{
    public function calcularValorTotal(Compromisso $compromisso)
    {
        $consultor = $compromisso->consultor;
        $valorPorHora = $consultor->valor_por_hora;
        $intervaloInicio = Carbon::parse($compromisso->intervalo_inicio);
        $intervaloFim = Carbon::parse($compromisso->intervalo_fim);
        $dataInicio = Carbon::parse($compromisso->data_inicio);
        $dataFim = Carbon::parse($compromisso->data_fim);

        $tempoTotalIntervalo = $intervaloInicio->diffInMinutes($intervaloFim);
        $tempoCompromissoComIntervalo = $dataInicio->diffInMinutes($dataFim);

        $tempoCompromissoMenosIntervalo = $tempoCompromissoComIntervalo - $tempoTotalIntervalo;

        $horasTrabalho = $tempoCompromissoMenosIntervalo / 60.0;

        $valorTotal = $horasTrabalho * $valorPorHora;

        $valorTotalArredondado = round($valorTotal, 2);

        return [
            'valor_total' => $valorTotalArredondado,
            'horas_trabalhadas' => $horasTrabalho,
            'minutos_restantes' => $tempoCompromissoMenosIntervalo % 60,
            'nome_completo_consultor' => $consultor->nome_completo,
        ];
    }
}