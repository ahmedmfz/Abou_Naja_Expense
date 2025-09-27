<?php

namespace Modules\Expense\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Expense\App\Models\Expense;
use Modules\Expense\Enums\CategoryEnum;


class ExpenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $category = fake()->randomElement(CategoryEnum::cases())->value;

        return [
            'id'           => (string) Str::uuid(),
            'title'        => $this->faker->sentence(3),
            'amount'       => $this->faker->randomFloat(2, 10, 1500),
            'category'     => $category,
            'expense_date' => Carbon::today()->subDays($this->faker->numberBetween(0, 90))->toDateString(),
            'notes'        => $this->faker->boolean ? $this->faker->sentence() : null,
        ];
    }
}

