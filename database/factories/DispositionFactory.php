<?php

namespace Database\Factories;

use App\Enums\DispositionStatusEnum;
use App\Enums\OperationRelationEnum;
use App\Models\Disposition;
use App\Models\StorageYard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DispositionFactory extends Factory
{
    protected $model = Disposition::class;

    public function definition()
    {
        return [
            'dis_month_number' => $this->faker->numberBetween(1, 1000),
            'dis_number' => 'DIS-' . $this->faker->unique()->randomNumber(6),
            'dis_yard_id' => StorageYard::factory(),
            'dis_relation_from' => $this->faker->randomElement([
                OperationRelationEnum::YARD->value,
                OperationRelationEnum::CARRIAGE->value,
                OperationRelationEnum::TRUCK->value
            ]),
            'dis_relation_to' => function (array $attributes) {
                if ($attributes['dis_relation_from'] == OperationRelationEnum::YARD->value) {
                    return $this->faker->randomElement([
                        OperationRelationEnum::CARRIAGE->value,
                        OperationRelationEnum::TRUCK->value
                    ]);
                }
                return OperationRelationEnum::YARD->value;
            },
            'dis_notes' => $this->faker->paragraph(),
            'dis_suggested_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'dis_status' => function () {
                return $this->faker->randomElement(DispositionStatusEnum::cases())->value;
            },
            'dis_created_by_id' => User::factory(),
            'dis_start_date' => null,
            'dis_completion_date' => null,
            'dis_cancel_date' => null,
        ];
    }

    public function notConfirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'dis_status' => DispositionStatusEnum::NOT_CONFIRMED->value,
            ];
        });
    }

    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'dis_status' => DispositionStatusEnum::CONFIRMED->value,
            ];
        });
    }

    public function processing()
    {
        return $this->state(function (array $attributes) {
            return [
                'dis_status' => DispositionStatusEnum::PROCESSING->value,
                'dis_start_date' => now(),
            ];
        });
    }

    public function finalized()
    {
        return $this->state(function (array $attributes) {
            return [
                'dis_status' => DispositionStatusEnum::FINALIZED->value,
                'dis_start_date' => now()->subDays(5),
                'dis_completion_date' => now(),
            ];
        });
    }

    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'dis_status' => DispositionStatusEnum::CANCELLED->value,
                'dis_cancel_date' => now(),
            ];
        });
    }
}