<?php

namespace Modules\Expense\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Expense\App\Models\Expense;
use Modules\Expense\Interfaces\ExpenseRepositoryInterface;
use Tests\TestCase;

class ExpenseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ExpenseRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = app(ExpenseRepositoryInterface::class);
    }

    public function test_it_paginates_expenses_with_date_and_category_filters(): void
    {

        Expense::create([
            'title'        => 'Old-Transport',
            'amount'       => 230,
            'category'     => 1,
            'expense_date' => Carbon::parse('2025-05-01'),
        ]);
        Expense::create([
            'title'        => 'In-Range-Transport-A',
            'amount'       => 230,
            'category'     => 1,
            'expense_date' => Carbon::parse('2025-05-15'),
        ]);
        Expense::create([
            'title'        => 'In-Range-Transport-B',
            'amount'       => 230,
            'category'     => 1,
            'expense_date' => Carbon::parse('2025-05-20'),
        ]);
        Expense::create([
            'title'        => 'In-Range-Food',
            'amount'       => 230,
            'category'     => 2,
            'expense_date' => Carbon::parse('2025-05-18'),
        ]);
        Expense::create([
            'title'        => 'After-Range-Transport',
            'amount'       => 230,
            'category'     => 2,
            'expense_date' => Carbon::parse('2025-06-01'),
        ]);

        // Act: filter 2025-05-10..2025-05-25 and category=1
        $result = $this->repo->paginate([
            'from'     => '2025-05-10',
            'to'       => '2025-05-25',
            'category' => 1,
        ], perPage: 15);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $titles = $result->pluck('title')->all();

        // Only "In-Range-Transport-A" and "In-Range-Transport-B" should be present
        $this->assertEqualsCanonicalizing(
            ['In-Range-Transport-A', 'In-Range-Transport-B'],
            $titles
        );

        // Make sure items fall within range and match category
        $result->each(function (Expense $e) {
            $this->assertTrue($e->expense_date->between(
                Carbon::parse('2025-05-10'),
                Carbon::parse('2025-05-25')
            ));
            $this->assertSame(1, (int) $e->category->value);
        });
    }

    public function test_paginates_with_custom_per_page_and_sorts_by_expense_date_desc(): void
    {
        Expense::factory()->create(['title' => 'D1', 'expense_date' => '2025-01-01', 'category' => 1]);
        Expense::factory()->create(['title' => 'D2', 'expense_date' => '2025-02-01', 'category' => 1]);
        Expense::factory()->create(['title' => 'D3', 'expense_date' => '2025-03-01', 'category' => 1]);

        $page = $this->repo->paginate([], perPage: 2);

        $this->assertInstanceOf(LengthAwarePaginator::class, $page);
        $this->assertSame(3, $page->total());
        $this->assertSame(2, $page->perPage());

        $titles = $page->getCollection()->pluck('title')->all();
        $this->assertSame(['D3', 'D2'], $titles);
    }

    public function test_stores_an_expense(): void
    {
        $data = [
            'title'        => 'New Expense',
            'amount'       => 123.45,
            'category'     => 2,
            'expense_date' => '2025-09-25',
            'notes'        => 'Test notes',
        ];

        $created = $this->repo->store($data);

        $this->assertInstanceOf(Expense::class, $created);
        $this->assertDatabaseHas('expenses', [
            'id'      => $created->id,
            'title'   => 'New Expense',
            'amount'  => 123.45,
            'category'=> 2,
            'notes'   => 'Test notes',
        ]);
        $this->assertTrue($created->expense_date->isSameDay('2025-09-25'));
    }

    public function test_updates_an_expense(): void
    {
        $expense = Expense::factory()->create([
            'title'        => 'Before',
            'amount'       => 10.00,
            'category'     => 1,
            'expense_date' => '2025-09-01',
            'notes'        => 'Old',
        ]);

        $payload = [
            'title'        => 'After',
            'amount'       => 99.99,
            'category'     => 2,
            'expense_date' => '2025-09-10',
            'notes'        => 'Updated',
        ];

        $updated = $this->repo->update($payload, $expense);

        $this->assertSame('After', $updated->title);
        $this->assertEquals(99.99, (float) $updated->amount);
        $this->assertSame(2, (int) $updated->category->value);
        $this->assertTrue($updated->expense_date->isSameDay('2025-09-10'));
        $this->assertSame('Updated', $updated->notes);

        $this->assertDatabaseHas('expenses', [
            'id'      => $expense->id,
            'title'   => 'After',
            'amount'  => 99.99,
            'category'=> 2,
            'notes'   => 'Updated',
        ]);
    }

    public function test_deletes_an_expense(): void
    {
        $expense = Expense::factory()->create();

        $deleted = $this->repo->delete($expense);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
        // If using SoftDeletes, replace with:
        // $this->assertSoftDeleted('expenses', ['id' => $expense->id]);
    }
}
