<?php

namespace Modules\Expense\Transformers\Expense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'amount'       => (float) $this->amount,
            'category'     => $this->category->name,
            'expense_date' => $this->expense_date,
            'notes'        => $this->notes ?? ""
        ];
    }
}
