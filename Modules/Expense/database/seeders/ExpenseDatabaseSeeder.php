<?php

namespace Modules\Expense\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Expense\Enums\CategoryEnum;
use Modules\Expense\App\Models\Expense;

class ExpenseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [];
        $categories = CategoryEnum::values();


        for ($i = 0; $i < 25; $i++) {
            $cat = $categories[array_rand($categories)];

            $rows[] = [
                'id'           => (string) Str::uuid(),
                'title'        => fake()->sentence(3),
                'amount'       => fake()->randomFloat(2, 10, 1500),
                'category'     => $cat,
                'expense_date' => Carbon::today()->subDays(rand(0, 90))->toDateString(),
                'notes'        => rand(0, 1) ? fake()->sentence() : null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        Expense::insert($rows);
    }
}
