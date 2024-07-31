<?php

namespace App\Http\Requests\Api\Empresa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    const OPERATORS = [
        'LIKE',
        'NOT LIKE',
        'IS NOT NULL',
        'IS NULL',
        'IN',
        '=',
        '<',
        '>',
        '<=',
        '>=',
        '<>',
        '!=',
    ];
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
            'page' => [
                'integer',
            ],
            'per_page' => [
                'integer',
            ],
            'fields' => [
                'array',
            ],
            'fields.*' => [
                'string'
            ],
            'where' => ['array'],
            'where.*' => ['array', 'size:3'],
            'where.*.0' => ['string'],
            'where.*.1' => [Rule::in(self::OPERATORS)],
            'where.*.2' => ['nullable'],
            'or_where' => ['array'],
            'or_where.*' => ['array', 'size:3'],
            'or_where.*.0' => ['string'],
            'or_where.*.1' => [Rule::in(self::OPERATORS)],
            'or_where.*.2' => ['nullable'],
            'order_by' => ['array', 'min:1'],
            'with' => ['array', 'min:1'],
        ];
    }
}
