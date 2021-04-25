<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_category' => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10]),
            'id_measurement_unit' => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10]), 
            'name' => Str::random(15),
            'description' => $this->faker->paragraph(1),
            'stock' => $this->faker->randomElement([100,200,50,24,500,32,76,29,92]),
            'unit_sale_price' => $this->faker->randomElement([10.5,20.2,5.3,2.3,50.5,3.2,7.6,2.9,9.2]),
            'unit_purchase_price' => $this->faker->randomElement([102.5,203.2,54.3,25.3,506.5,37.2,78.6,29.9,90.2]),
            'status' => $this->faker->randomElement([true,false]),
        ];
    }
}
