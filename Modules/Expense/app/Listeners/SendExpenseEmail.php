<?php

namespace Modules\Expense\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Expense\app\Emails\ExpenseCreatedMail;
use Modules\Expense\app\Events\ExpenseCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendExpenseEmail
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    public function handle(ExpenseCreated $event): void
    {
        Mail::to($event->user->email)->queue(
            new ExpenseCreatedMail($event->expense)
        );
    }
}
