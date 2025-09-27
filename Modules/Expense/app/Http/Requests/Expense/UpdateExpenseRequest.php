<?php

namespace Modules\Expense\Http\Requests\Expense;

use Illuminate\Validation\Rule;
use Modules\Expense\app\Http\Requests\BaseApiRequest;
use Modules\Expense\Enums\CategoryEnum;

class UpdateExpenseRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
             'title'        => 'required|string',
             'amount'       => 'required|numeric',
             'category'     => ['required', 'integer', Rule::enum(CategoryEnum::class)],
             'expense_date' => 'required|date',
             'notes'        => 'nullable|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
