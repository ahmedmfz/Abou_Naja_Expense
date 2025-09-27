<?php

namespace Modules\Expense\Http\Requests\Expense;

use Illuminate\Validation\Rule;
use Modules\Expense\app\Http\Requests\BaseApiRequest;
use Modules\Expense\Enums\CategoryEnum;

class ViewExpenseRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
             'per_page'   => 'nullable|integer|between:1,100',
             'page'       => 'nullable|integer|min:1',
             'from'       => 'nullable|date',
             'to'         => 'nullable|date',
             'category'   => ['nullable', 'integer', Rule::enum(CategoryEnum::class)],
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
