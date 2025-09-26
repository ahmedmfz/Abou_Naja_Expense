<?php

namespace Modules\Expense\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expense\App\Models\Expense;


interface ExpenseServiceInterface {
    public function viewAll($data , int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): Expense;
    public function update(Expense $expense, array $data): Expense;
    public function delete(Expense $expense): void;

}
