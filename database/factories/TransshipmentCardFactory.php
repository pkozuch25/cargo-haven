<?php

namespace Database\Factories;

use App\Models\TransshipmentCard;
use App\Models\StorageYard;
use App\Models\User;
use App\Enums\OperationRelationEnum;
use App\Enums\TransshipmentCardStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransshipmentCardFactory extends Factory
{
    protected $model = TransshipmentCard::class;

    public function definition()
    {
        return [
            'tc_number' => 'TC-' . $this->faker->unique()->numberBetween(10000, 99999),
            'tc_relation_from' => $this->faker->randomElement([
                OperationRelationEnum::YARD->value,
                OperationRelationEnum::CARRIAGE->value,
                OperationRelationEnum::TRUCK->value
            ]),
            'tc_relation_to' => function (array $attributes) {
                if ($attributes['tc_relation_from'] == OperationRelationEnum::YARD->value) {
                    return $this->faker->randomElement([
                        OperationRelationEnum::CARRIAGE->value,
                        OperationRelationEnum::TRUCK->value
                    ]);
                }
                return OperationRelationEnum::YARD->value;
            },
            'tc_created_by_user' => User::factory(),
            'tc_yard_id' => StorageYard::factory(),
            'tc_status' => $this->faker->randomElement([
                TransshipmentCardStatusEnum::PROCESSING->value,
                TransshipmentCardStatusEnum::COMPLETED->value,
            ]),
            'tc_notes' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function processing()
    {
        return $this->state(function (array $attributes) {
            return [
                'tc_status' => TransshipmentCardStatusEnum::PROCESSING->value,
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'tc_status' => TransshipmentCardStatusEnum::COMPLETED->value,
            ];
        });
    }
}
