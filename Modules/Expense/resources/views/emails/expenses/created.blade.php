<p>Hi,</p>
<p>A new expense was created:</p>
<ul>
    <li>Title: {{ $expense->title }}</li>
    <li>Amount: {{ $expense->amount }}</li>
    <li>Date: {{ $expense->expense_date->toDateString() }}</li>
</ul>
