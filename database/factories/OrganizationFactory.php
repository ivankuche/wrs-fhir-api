<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
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
            'country'=> $this->faker->country()
        ];

        return [
            'active'=>true,
            'name'=>$this->faker->company,
            'telecom'=> [[
                "system"=>"phone",
                "value"=>$this->faker->phoneNumber,
            ],
            [
                "system"=>"phone",
                "value"=>$this->faker->phoneNumber,
            ]],
            'address'=>[$addressItem],
        ];
    }
}
