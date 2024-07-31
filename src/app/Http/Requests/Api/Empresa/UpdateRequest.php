<?php

namespace App\Http\Requests\Api\Empresa;

use App\Rules\CnpjRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'razao_social' => [
               'required',
               'string',
               'max:120'
            ],
            'nome_fantasia' => [
               'nullable',
               'string',
               'max:120'
            ],
            'cnpj' => [
               'required',
               new CnpjRule,
               Rule::unique('empresas', 'cnpj')->ignore($this->id)
            ],
           'status' => [
                Rule::in(['ativa', 'desabilitada', 'pendente'])
            ]
        ];
    }
}
