<?php

namespace Modules\Expense\app\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Expense\App\Models\Expense;



class ExpenseCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Expense $expense) {}


    public function build()
    {
        return $this->subject('New Expense Created')
            ->view('Expense::emails.expenses.created')
            ->with(['expense' => $this->expense]);
    }
}
