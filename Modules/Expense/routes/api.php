<?php

use Illuminate\Routing;
use Modules\Expense\Http\Controllers\ExpenseController;

Route::prefix('expenses')->group(function (){
    Route::get('/', [ExpenseController::class, 'index']);
    Route::post('store', [ExpenseController::class, 'store']);
});



