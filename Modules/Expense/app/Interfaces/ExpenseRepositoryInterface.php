<?php

namespace Modules\Expense\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expense\App\Enums\Category;
use Modules\Expense\App\Models\Expense;


interface ExpenseRepositoryInterface {
    public function paginate(array $data  , ?int $perPage): LengthAwarePaginator;
    public function store(array $data): Expense;
    public function update(array $data ,Expense $expense): Expense;
    public function delete(Expense $expense): bool;
}
