<?php

namespace Modules\Expense\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Expense\App\Models\Expense;

class ExpenseCreatedNotification  extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Expense $expense) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'expense_id'   => $this->expense->id,
            'title'        => $this->expense->title,
            'amount'       => $this->expense->amount,
            'category'     => $this->expense->category->name,
            'expense_date' => $this->expense->expense_date?->toDateString(),
            'message'      => "Expense \"{$this->expense->title}\" was created.",
        ];
    }
}
