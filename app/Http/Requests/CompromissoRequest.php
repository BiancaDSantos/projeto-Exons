<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompromissoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return 
        [
            'data_inicio' => 'required|date_format:Y-m-d H:i:s|before:data_fim',
            'data_fim' => 'required|date_format:Y-m-d H:i:s|after:data_inicio',
            'intervalo_inicio' => 'required|date_format:Y-m-d H:i:s',
            'intervalo_fim' => 'required|date_format:Y-m-d H:i:s',
            'id_consultor' => 'required|exists:consultor,id_consultor|integer',
        ];
    }

    public function messages(){
        return [
            'data_inicio.required' => 'A data de início é obrigatória.',
            'data_inicio.date_format' => 'A data de início deve ser uma data válida.',
            'data_inicio.before' => 'A data de início do compromisso deve ser anterior à data de término do compromisso.',
            'data_fim.required' => 'A data de fim é obrigatória.',
            'data_fim.date_format' => 'A data de fim deve ser uma data válida.',
            'data_fim.after' => 'A data de início do compromisso deve ser anterior à data de término do compromisso.',
            'intervalo_inicio.required' => 'O horário de início do intervalo é obrigatório.',
            'intervalo_inicio.date_format' => 'O horário de início do intervalo deve estar no formato Y-m-d H:i:s.',
            'intervalo_inicio.after_or_equal' => 'O horário de início do intervalo deve ser posterior ou igual à data de início do compromisso.',
            'intervalo_inicio.before_or_equal' => 'O horário de início do intervalo deve ser anterior ou igual à data de fim do compromisso.',
            'intervalo_fim.required' => 'O horário de fim do intervalo é obrigatório.',
            'intervalo_fim.date_format' => 'O horário de fim do intervalo deve estar no formato Y-m-d H:i:s.',
            'intervalo_fim.after' => 'O horário de fim do intervalo deve ser posterior ao horário de início do intervalo.',
            'intervalo_fim.before_or_equal' => 'O horário de fim do intervalo deve ser anterior ou igual à data de fim do compromisso.',
            'consultor_id.required' => 'O consultor é obrigatório.',
            'consultor_id.exists' => 'O consultor selecionado não existe.',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function($validator) {
            $dataInicio = Carbon::parse($this->input('data_inicio'));
            $dataFim = Carbon::parse($this->input('data_fim'));
            $intervaloInicio = Carbon::parse($this->input('intervalo_inicio'));
            $intervaloFim = Carbon::parse($this->input('intervalo_fim'));

            $tempoCompromissoComIntervalo = $dataInicio->diffInMinutes($dataFim);
            //600 equivale há 10 horas
            if($tempoCompromissoComIntervalo > 600) {
                $validator->errors()->add('data_fim', 'A duração do compromisso deve ser igual ou menor que dez horas.');
            }

            if($intervaloInicio->greaterThan($intervaloFim)) {
                $validator->errors()->add('intervalo_fim', 'O hórario de término do intervalo deve acontecer antes do fim do compromisso.');
            }

            $tempoDeIntervalo = $intervaloInicio->diffInMinutes($intervaloFim);
            if($tempoDeIntervalo < 5) {
                $validator->errors()->add('intervalo_fim', 'O hórario de término do intervalo deve ser no mínimo cinco minutos após seu ínicio.');
            }

            if($tempoDeIntervalo > 120) {
                $validator->errors()->add('intervalo_fim', 'A duração do intervalo não pode ser maior que duas horas.');
            }

            if($intervaloInicio->lessThan($dataInicio)) {
                $validator->errors()->add('intervalo_inicio', 'O intervalo deve estar entre o período do compromisso.');
            }
            
            if($intervaloFim->greaterThanOrEqualTo($dataFim)) {
                $validator->errors()->add('intervalo_fim', 'O horário de término do intervalo deve acontecer antes do fim do compromisso.');
            }
        });
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
