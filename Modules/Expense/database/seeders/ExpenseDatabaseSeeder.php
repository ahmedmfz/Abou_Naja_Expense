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
        Expense::factory()
            ->count(20)
            ->create();
    }
}
