<?php

namespace Modules\Expense\Services;


use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expense\Interfaces\ExpenseRepositoryInterface;
use Modules\Expense\Interfaces\ExpenseServiceInterface;
use Modules\Expense\App\Models\Expense;


class ExpenseService implements ExpenseServiceInterface
{
    public function __construct(private ExpenseRepositoryInterface $repo) {}

    public function viewAll($data , int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($data , $perPage);
    }

    public function create(array $data): Expense
    {
        if (isset($data['category']) && $data['category'] instanceof Category) {
            $data['category'] = $data['category']->value;
        }

        return $this->repo->store($data);
    }

    public function update(Expense $expense, array $data): Expense
    {
        if (isset($data['category']) && $data['category'] instanceof Category) {
            $data['category'] = $data['category']->value;
        }
        return $this->repo->update($expense->id, $data);
    }

    public function delete(Expense $expense): void
    {
        $this->repo->delete($expense->id);
    }
}
