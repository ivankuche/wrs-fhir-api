<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'distinctIdentifier' => $this->faker->asciify('********************'),
            'expirationDate' => $this->faker->date(),
            'lotNumber' => $this->faker->asciify('********************'),
            'manufactureDate' => $this->faker->date(),
            'serialNumber' => $this->faker->asciify('********************'),
            'type' => [
                'coding' => [
                    "system" => "http://snomed.info/sct",
                    "code" => "19257004",
                    "display" => "Defibrillator, device"
                ]
            ],
            'udiCarrier' => [
                'carrierHRF' => $this->faker->asciify('********************'),
                'deviceIdentifier' => $this->faker->asciify('********************'),
            ]
        ];
    }
}
