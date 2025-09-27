<?php

namespace Modules\Expense\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Expense\Database\Factories\ExpenseFactory;
use Modules\Expense\Enums\CategoryEnum;


class Expense extends Model
{
    use HasFactory , HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = [
        'category'     => CategoryEnum::class,
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    protected static function newFactory(): ExpenseFactory
    {
        return ExpenseFactory::new();
    }
}
