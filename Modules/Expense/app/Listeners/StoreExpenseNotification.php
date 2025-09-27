<?php

namespace Modules\Expense\Listeners;

use Modules\Expense\app\Events\ExpenseCreated;
use Modules\Expense\Notifications\ExpenseCreatedNotification;


class StoreExpenseNotification
{
    /**
     * Create the event listener.
     */
    public function __construct() {}


    public function handle(ExpenseCreated $event): void
    {
        $event->user->notify(new ExpenseCreatedNotification($event->expense));
    }
}
