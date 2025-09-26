<?php

namespace Modules\Expense\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Expense\App\Enums\Category;
use Modules\Expense\App\Models\Expense;
use Carbon\Carbon;

interface ExpenseRepositoryInterface {

    public function paginate(?int $perPage): LengthAwarePaginator;

    public function store(array $data): Expense;
    public function update(string $id, array $data): Expense;
    public function delete(string $id): bool;
}
