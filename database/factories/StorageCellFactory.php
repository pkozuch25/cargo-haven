<?php

namespace Database\Factories;

use App\Models\StorageCell;
use App\Models\StorageYard;
use Illuminate\Database\Eloquent\Factories\Factory;

class StorageCellFactory extends Factory
{
    protected $model = StorageCell::class;

    public function definition()
    {
        return [
            'sc_yard_id' => StorageYard::factory(),
            'sc_yard_name_short' => $this->faker->regexify('[A-Z]{3}'),
            'sc_cell' => $this->faker->numberBetween(1, 999),
            'sc_row' => $this->faker->randomLetter() . $this->faker->randomLetter(),
            'sc_height' => $this->faker->numberBetween(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
