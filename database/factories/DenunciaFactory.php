<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DenunciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'idUsuario' => '34',
            'tipo' => fake()->name(),
            'cor' => 'caramelo',
            'rua' => fake()->locale(),
            'bairro' => fake()->locale(),
            'pontoDeReferencia' => fake()->locale(),
            'picture1' => fake()->name().'.png',
            'picture2' => fake()->name().'.png',
            'picture3' => fake()->name().'.png',
            'descricao' => fake()->name().'machucado'
        ];
    }
}
