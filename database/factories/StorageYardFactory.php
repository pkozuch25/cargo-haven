<?php

namespace Database\Factories;

use App\Models\StorageYard;
use Illuminate\Database\Eloquent\Factories\Factory;

class StorageYardFactory extends Factory
{
    protected $model = StorageYard::class;

    public function definition()
    {
        return [
            'sy_name' => $this->faker->company() . ' Yard',
            'sy_name_short' => strtoupper($this->faker->lexify('???')),
            'sy_length' => $this->faker->numberBetween(100, 1000),
            'sy_width' => $this->faker->numberBetween(100, 1000),
            'sy_cell_count' => $this->faker->numberBetween(10, 100),
            'sy_row_count' => $this->faker->numberBetween(5, 50),
            'sy_height' => $this->faker->numberBetween(5, 20),
        ];
    }
}