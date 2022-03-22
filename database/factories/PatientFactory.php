<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'active'=> $this->faker->boolean(),
            'name'=> $this->faker->firstName($gender),
            'surname'=> $this->faker->lastName(),
            'gender'=>$gender,
            'birthdate'=>$this->faker->date('Y-m-d','now - 5 year')
            //
        ];
    }
}
