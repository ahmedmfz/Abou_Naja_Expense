<?php

namespace Modules\Expense\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expense\App\Models\Expense;
use Modules\Expense\Interfaces\ExpenseRepositoryInterface;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    protected $model = Expense::class;

    public function paginate($data = [] , ?int $perPage = 20): LengthAwarePaginator
    {
        $query =  $this->model::query();

        $query->when($data['from'], function ($query) use ($data) {
            return $query->whereDate('expense_date', '>=', $data['from']);
        })->when($data['to'], function ($query) use ($data) {
            return $query->whereDate('expense_date', '<=', $data['to']);
        })->when($data['category'], function ($query) use ($data) {
            $query->where('category', $data['category']);
        })->latest('expense_date');

        return $query->paginate($perPage);
    }

    public function store(array $data): Expense
    {
        return $this->model::create($data);
    }

    public function update(string $id, array $data): Expense
    {
        $expense = $this->model::query()->findOrFail($id);
        $expense->update($data);
        return $expense;
    }

    public function delete(string $id): bool
    {
        $expense = $this->model::findOrFail($id);
        return (bool) $expense->delete();
    }
}
