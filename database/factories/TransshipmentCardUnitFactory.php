<?php

namespace Database\Factories;

use App\Models\TransshipmentCardUnit;
use App\Models\TransshipmentCard;
use App\Models\Disposition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransshipmentCardUnitFactory extends Factory
{
    protected $model = TransshipmentCardUnit::class;

    public function definition()
    {
        return [
            'tcu_tc_id' => TransshipmentCard::factory(),
            'tcu_operator_id' => User::factory(),
            'tcu_disp_id' => Disposition::factory(),
            'tcu_container_number' => 'CONT-' . $this->faker->unique()->numberBetween(10000, 99999),
            'tcu_yard_position' => $this->faker->randomLetter() . $this->faker->randomLetter() . '-' . $this->faker->numberBetween(1, 100),
            'tcu_carriage_number_from' => $this->faker->boolean(50) ? 'CAR-' . $this->faker->numberBetween(1000, 9999) : null,
            'tcu_carriage_number_to' => $this->faker->boolean(50) ? 'CAR-' . $this->faker->numberBetween(1000, 9999) : null,
            'tcu_truck_number_from' => $this->faker->boolean(50) ? 'TRK-' . $this->faker->numberBetween(1000, 9999) : null,
            'tcu_truck_number_to' => $this->faker->boolean(50) ? 'TRK-' . $this->faker->numberBetween(1000, 9999) : null,
            'tcu_gross_weight' => $this->faker->numberBetween(1000, 5000),
            'tcu_tare_weight' => $this->faker->numberBetween(100, 500),
            'tcu_net_weight' => function (array $attributes) {
                return $attributes['tcu_gross_weight'] - $attributes['tcu_tare_weight'];
            },
            'tcu_notes' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function fromCarriageTransport()
    {
        return $this->state(function (array $attributes) {
            return [
                'tcu_carriage_number_from' => 'CARRIAGE-' . $this->faker->numberBetween(1000, 9999)
            ];
        });
    }

    public function toCarriageTransport()
    {
        return $this->state(function (array $attributes) {
            return [
                'tcu_carriage_number_to' => 'CARRIAGE-' . $this->faker->numberBetween(1000, 9999),
            ];
        });
    }

    public function fromTruckTransport()
    {
        return $this->state(function (array $attributes) {
            return [
                'tcu_truck_number_from' => 'TRK-' . $this->faker->numberBetween(1000, 9999),
            ];
        });
    }

    public function toTruckTransport()
    {
        return $this->state(function (array $attributes) {
            return [
                'tcu_truck_number_to' => 'TRK-' . $this->faker->numberBetween(1000, 9999),
            ];
        });
    }
}
