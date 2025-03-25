<?php

namespace Database\Factories;

use App\Models\Deposit;
use App\Models\DispositionUnit;
use App\Models\StorageCell;
use App\Models\TransshipmentCard;
use App\Models\TransshipmentCardUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    protected $model = Deposit::class;

    public function definition()
    {
        return [
            'dep_sc_id' => StorageCell::factory(),
            'dep_number' => 'TEST-' . $this->faker->unique()->numberBetween(10000, 99999),
            'dep_arrival_date' => $this->faker->date(),
            'dep_departure_date' => null,
            'dep_arrival_disu_id' => null,
            'dep_departure_disu_id' => null,
            'dep_arrival_card_id' => $this->faker->numberBetween(1, 1000),
            'dep_arrival_cardunit_id' => $this->faker->numberBetween(1, 1000),
            'dep_departure_card_id' => null,
            'dep_departure_cardunit_id' => null,
            'dep_gross_weight' => $this->faker->numberBetween(1000, 5000),
            'dep_tare_weight' => $this->faker->numberBetween(100, 500),
            'dep_net_weight' => function (array $attributes) {
                return $attributes['dep_gross_weight'] - $attributes['dep_tare_weight'];
            },
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withArrivalCard()
    {
        return $this->state(function (array $attributes) {
            return [
                'dep_arrival_card_id' => TransshipmentCard::factory()
            ];
        });
    }

    public function withDepartureCard()
    {
        return $this->state(function (array $attributes) {
            return [
                'dep_departure_card_id' => TransshipmentCard::factory()
            ];
        });
    }

    public function withArrivalCardUnit()
    {
        return $this->state(function (array $attributes) {
            return [
                'dep_arrival_cardunit_id' => TransshipmentCardUnit::factory()
            ];
        });
    }

    public function withDepartureCardUnit()
    {
        return $this->state(function (array $attributes) {
            return [
                'dep_departure_cardunit_id' => TransshipmentCardUnit::factory()
            ];
        });
    }

    public function withArrivalDispositionUnit()
    {
        return $this->state(function (array $attributes) {
            return [
                'dep_arrival_disu_id' => DispositionUnit::factory()
            ];
        });
    }

    public function withDepartureDispositionUnit()
    {
        return $this->state(function (array $attributes) {
            return [
                'dep_departure_disu_id' => DispositionUnit::factory()
            ];
        });
    }
}
