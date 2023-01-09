<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'idAdmin' => '4',
            'idDenuncia' => '4',
            'admin' => fake()->name(),
            'org' => 'ong',
            'status' => 'Denuncia aceita',
            'message' => 'Obrigado',
        ];
    }
}
