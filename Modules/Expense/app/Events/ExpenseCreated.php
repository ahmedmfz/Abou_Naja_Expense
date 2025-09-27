<?php

namespace Modules\Expense\app\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Expense\App\Models\Expense;

class ExpenseCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Expense $expense,
        public User $user,
    ) {}
}
