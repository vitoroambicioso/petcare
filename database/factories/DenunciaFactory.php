<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Denuncia>
 */
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
            'name' => $this->faker->name(),
            'raca' => $this->faker->name(),
            'situacao' => $this->faker->name(),
            'descricao' => $this->faker->name(),
            'localizacao' => $this->faker->name(),
        ];
    }
}
