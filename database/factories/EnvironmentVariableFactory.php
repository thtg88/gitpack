<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\EnvironmentVariable;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnvironmentVariableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EnvironmentVariable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'app_id' => static function () {
                return App::factory()->create()->id;
            },
            'name' => strtoupper(implode('_', $this->faker->words(3))),
            'value' => $this->faker->paragraph,
        ];
    }
}
