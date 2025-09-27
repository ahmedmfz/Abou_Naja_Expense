<?php

namespace Modules\Expense\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Expense\app\Events\ExpenseCreated;
use Modules\Expense\Listeners\SendExpenseEmail;
use Modules\Expense\Listeners\StoreExpenseNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        ExpenseCreated::class => [
            StoreExpenseNotification::class,
            SendExpenseEmail::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
