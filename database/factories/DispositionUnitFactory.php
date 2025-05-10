<?php

namespace Database\Factories;

use App\Models\Disposition;
use App\Models\DispositionUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class DispositionUnitFactory extends Factory
{
    protected $model = DispositionUnit::class;

    public function definition()
    {
        return [
            'disu_dis_id' => Disposition::factory(),
            'disu_status' => 0,
            'disu_car_number' => $this->faker->bothify('??-###'),
            'disu_carriage_number' => $this->faker->bothify('CAR-####'),
            'disu_container_number' => $this->faker->bothify('CONT-####'),
            'disu_container_net_weight' => $this->faker->numberBetween(1000, 20000),
            'disu_container_gross_weight' => function (array $attributes) {
                return $attributes['disu_container_net_weight'] + $this->faker->numberBetween(500, 2000);
            },
            'disu_container_tare_weight' => $this->faker->numberBetween(500, 2000),
            'disu_notes' => $this->faker->paragraph(),
            'disu_driver' => $this->faker->name(),
            'disu_cardunit_id' => null,
        ];
    }

    public function withDisposition(Disposition $disposition)
    {
        return $this->state(function (array $attributes) use ($disposition) {
            return [
                'disu_dis_id' => $disposition->dis_id,
            ];
        });
    }

    public function withCarNumber(string $carNumber)
    {
        return $this->state(function (array $attributes) use ($carNumber) {
            return [
                'disu_car_number' => $carNumber,
            ];
        });
    }

    public function withCarriageNumber(string $carriageNumber)
    {
        return $this->state(function (array $attributes) use ($carriageNumber) {
            return [
                'disu_carriage_number' => $carriageNumber,
            ];
        });
    }

    public function withContainerNumber(string $containerNumber)
    {
        return $this->state(function (array $attributes) use ($containerNumber) {
            return [
                'disu_container_number' => $containerNumber,
            ];
        });
    }

    public function withWeights(int $netWeight, int | null $tareWeight)
    {
        return $this->state(function (array $attributes) use ($netWeight, $tareWeight) {
            $tareWeight = $tareWeight ?? $this->faker->numberBetween(500, 2000);
            return [
                'disu_container_net_weight' => $netWeight,
                'disu_container_tare_weight' => $tareWeight,
                'disu_container_gross_weight' => $netWeight + $tareWeight,
            ];
        });
    }

    public function withDriver(string $driver)
    {
        return $this->state(function (array $attributes) use ($driver) {
            return [
                'disu_driver' => $driver,
            ];
        });
    }
}
