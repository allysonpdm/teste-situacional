<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfCnpjRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->isValid($value)){
            $fail('O CPF/CNPJ não é válido.');
        }
    }

    public function isValid(string $value): bool
    {
        $cpfRule = new CpfRule();
        if ($cpfRule->isValid($value)) {
            return true;
        }

        $cnpjRule = new CnpjRule();
        if ($cnpjRule->isValid($value)) {
            return true;
        }

        return false;
    }
}
