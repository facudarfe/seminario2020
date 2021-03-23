<?php

namespace Database\Factories;

use App\Models\PropuestaTema;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropuestaTemaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PropuestaTema::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence(),
            'descripcion' => $this->faker->paragraph(),
            'tecnologias' => $this->faker->name(),
            'docente_id' => 2,
            'estado_id' => 8
        ];
    }
}
