<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\currencies>
 */
class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition()
    {
        $country = Country::whereNotIn(
            'currency_code',
            Currency::pluck('code')->toArray()
        )->inRandomOrder()->first();

        return [
            'code' => $country->currency_code,
            'name' => $country->currency,
            'symbol' => $this->faker->unique()->randomLetter,
            'is_default' => Currency::default()->first() === null,
        ];
    }
}
