<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EmpresaFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'nome_fantasia' => $this->faker->name(),
            'razao_social' => $this->faker->name(),
            'cnpj' => $this->faker->cnpj(false),
            'status' => $this->faker->randomElement(['ativa', 'desabilitada', 'pendente']),
        ];
    }
}
