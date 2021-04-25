<?php

namespace Database\Factories;

use App\Models\MeasurementUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeasurementUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeasurementUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'abbreviation' => $this->faker->randomElement(['KG','ML','MG','AS','ASD']),
            'detail' => "Detalles",
        ];
    }
}
