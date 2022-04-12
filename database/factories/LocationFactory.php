<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $address= $this->faker->streetName;
        $number= $this->faker->buildingNumber;
        $city= $this->faker->city;
        $state= $this->faker->state;
        $postCode= $this->faker->postcode;
        $periodStart= $this->faker->date('Y-m-d','now - 3 year');

        $addressItem= [
            'type'=>'physical',
            'text'=>$number." ".$address.", ".$city.", ".$state." ".$postCode,
            'line'=> [
                $number." ".$address
            ],
            'city'=>$city,
            'state'=>$state,
            'postalCode'=>$postCode,
            'period'=>[
                'start'=>$periodStart
            ],
        ];


        return [
            'status' => $this->faker->randomElement(['active', 'suspended', 'inactive']),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'telecom'=> [
                [
                    "system"=>"phone",
                    "value"=>$this->faker->phoneNumber,
                ],
            ],
            'address' => $addressItem,
            //
        ];
    }
}
