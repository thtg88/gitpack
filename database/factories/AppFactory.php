<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\User;
use Illuminate\Support\Str;

class AppFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = App::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => Str::slug($this->faker->words(rand(1, 3), true)),
            'user_id' => static function (array $data) {
                return factory(User::class)->create()->id;
            },
        ];
    }
}
