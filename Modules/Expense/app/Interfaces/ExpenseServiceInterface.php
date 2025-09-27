<?php

namespace Modules\Expense\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expense\App\Models\Expense;


interface ExpenseServiceInterface {
    public function viewAll(array $data , int $perPage): LengthAwarePaginator;
    public function create(array $data): Expense;
    public function update(array $data , Expense $expense): Expense;
    public function delete(Expense $expense): void;
}
