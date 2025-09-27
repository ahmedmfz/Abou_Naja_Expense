<?php

namespace Modules\Expense\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expense\App\Models\Expense;
use Modules\Expense\Interfaces\ExpenseRepositoryInterface;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    protected $model = Expense::class;

    public function paginate(array $data =[], ?int $perPage = 15): LengthAwarePaginator
    {
        $query =  $this->model::query();

        $query->when(isset($data['from']) && !is_null($data['from']), function ($query) use ($data) {
                return $query->whereDate('expense_date', '>=', $data['from']);
            })->when(isset($data['to']) && !is_null($data['to']), function ($query) use ($data) {
                return $query->whereDate('expense_date', '<=', $data['to']);
            })->when(isset($data['category']) && !is_null($data['category']), function ($query) use ($data) {
                $query->where('category', $data['category']);
            })
            ->latest('expense_date');

        return $query->paginate($perPage);
    }

    public function store(array $data): Expense
    {
        return $this->model::create($data);
    }

    public function update(array $data , Expense $expense): Expense
    {
        $expense->update($data);
        return $expense;
    }

    public function delete(Expense $expense): bool
    {
        return (bool) $expense->delete();
    }
}
