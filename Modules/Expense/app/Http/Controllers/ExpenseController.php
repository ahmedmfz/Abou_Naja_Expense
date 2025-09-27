<?php

namespace Modules\Expense\Http\Controllers;


use App\Helper\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Modules\Expense\Http\Requests\Expense\StoreExpenseRequest;
use Modules\Expense\Http\Requests\Expense\UpdateExpenseRequest;
use Modules\Expense\Http\Requests\Expense\ViewExpenseRequest;
use Modules\Expense\Interfaces\ExpenseServiceInterface;
use Modules\Expense\App\Models\Expense;
use Modules\Expense\Transformers\Expense\ExpenseCollection;
use Modules\Expense\Transformers\Expense\ExpenseResource;


class ExpenseController extends Controller
{
    public function __construct(private ExpenseServiceInterface $service) {}

    /**
     * @OA\Get(
     *     path="/api/expenses",
     *     summary="expenses api",
     *     tags={"expenses"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="count of rows in a single page",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="1,2,3",
     *         required=false,
     *      @OA\Schema(type="number")
     *      ),
     *      @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="2025-05-25",
     *          required=false,
     *       @OA\Schema(type="date")
     *       ),
     *       @OA\Parameter(
     *           name="to",
     *           in="query",
     *           description="2025-05-30",
     *           required=false,
     *        @OA\Schema(type="date")
     *        ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="422", description="Invalid Request Validation"),
     * )
     */
    public function index(ViewExpenseRequest $request)
    {
       $expenses = $this->service->viewAll($request->validated() , $request->per_page ?? 15);

        return ApiResponseHelper::returnJSON(
            new ExpenseCollection($expenses)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/expenses",
     *     summary="Store Expenses",
     *     tags={"expenses"},
     * @OA\RequestBody(
     *    @OA\JsonContent(
     *       required={"title","amount","category","expense_date"},
     *       @OA\Property(property="title", type="string", format="text", example="Test Expenese"),
     *       @OA\Property(property="amount", type="string", format="number", example="230"),
     *       @OA\Property(property="category", type="string", format="number", example="5"),
     *       @OA\Property(property="expense_date", type="string", format="date", example="2025-01-01"),
     *       @OA\Property(property="notes", type="string", format="text", example="Test Expenese notes"),
     *    ),
     * ),
     *     @OA\Response(response="201", description="Expenese created successfully"),
     *     @OA\Response(response="422", description="Invalid Request Validation"),
     * )
     */
    public function store(StoreExpenseRequest $request) {
        $expense = $this->service->create($request->validated());

        return ApiResponseHelper::returnJSON(
           new ExpenseResource($expense)
        );
    }

    /**
     * @OA\Put(
     *     path="/api/expenses/{id}",
     *     summary="Update an expense (full update)",
     *     tags={"expenses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Expense UUID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","amount","category","expense_date"},
     *             @OA\Property(property="title", type="string", example="Taxi to client site"),
     *             @OA\Property(property="amount", type="number", format="float", example=230.50),
     *             @OA\Property(property="category", type="integer", example=5),
     *             @OA\Property(property="expense_date", type="string", format="date", example="2025-01-01"),
     *             @OA\Property(property="notes", type="string", example="Paid cash")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Expense updated successfully"),
     *     @OA\Response(response=404, description="Expense not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateExpenseRequest $request, Expense $expense) {
       $updatedExpense = $this->service->update($request->validated(), $expense);

        return ApiResponseHelper::returnJSON(
            new ExpenseResource($updatedExpense)
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/expenses/{id}",
     *     summary="Delete an expense",
     *     tags={"expenses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Expense UUID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Deleted"),
     *     @OA\Response(response=404, description="Expense not found")
     * )
     */
    public function destroy(Expense $expense) {
        $this->service->delete($expense);
        return ApiResponseHelper::returnSuccessMessage(
           'Expense Has Been Deleted'
        );
    }
}
