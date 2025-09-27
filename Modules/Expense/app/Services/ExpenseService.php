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
        return $this->repo->store($data);
    }

    public function update(array $data ,Expense $expense): Expense
    {
        return $this->repo->update($data ,$expense);
    }

    public function delete(Expense $expense): void
    {
        $this->repo->delete($expense);
    }
}
