<?php

namespace Modules\Expense\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Expense\Interfaces\ExpenseServiceInterface;
use Modules\Expense\App\Models\Expense;


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
     *      @OA\Parameter(
     *         name="Category",
     *         in="header",
     *         description=" 1,2,3",
     *         required=false,
     *      @OA\Schema(type="number")
     *      ),
     *      @OA\Parameter(
     *          name="Date",
     *          in="header",
     *          description="20/05/2025-21-05-2025",
     *          required=false,
     *       @OA\Schema(type="number")
     *       ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function index(Request $request)
    {
       return $this->service->viewAll($request);
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
     *       @OA\Property(property="expense_date", type="string", format="date", example="1990-01-01"),
     *       @OA\Property(property="notes", type="string", format="text", example="Test Expenese notes"),
     *    ),
     * ),
     *     @OA\Response(response="201", description="User registered successfully"),
     *     @OA\Response(response="422", description="Invalid Validation"),
     * )
     */
    public function store(Request $request) {
       return $this->service->create($request);
    }

    public function update(Request $request, Expense $expense) {
       return $this->service->update($request, $expense);
    }

    public function destroy(Expense $expense) {
       return $this->service->delete($expense);
    }

}
