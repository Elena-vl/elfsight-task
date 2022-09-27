<?php

namespace Database\Factories;

use App\Models\Reviews;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика для модели "Reviews"
 *
 * Class ReviewFactory
 *
 * @package Database\Factories
 * @extends Factory
 */
class ReviewsFactory extends Factory
{
    /**
     * Название модели, соответствующей фабрике.
     *
     * @var string
     */
    protected $model = Reviews::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'episode' => $this->faker->numberBetween(1, 10),
            'comment' => $this->faker->text(50),
            'rating' => $this->faker->randomFloat(4, 0, 2),
        ];
    }
}
